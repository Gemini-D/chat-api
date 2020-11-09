<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Chat\Handler;

use App\Chat\HandlerInterface;
use App\Model\User;
use App\Service\Formatter\UserFormatter;
use App\Service\UserDataService;
use App\Service\UserServiceInterface;
use Hyperf\Di\Annotation\Inject;
use Swoole\WebSocket\Server;

class UserListHandler implements HandlerInterface
{
    /**
     * @Inject
     * @var UserDataService
     */
    protected $service;

    /**
     * @Inject
     * @var UserServiceInterface
     */
    protected $userService;

    /**
     * @Inject
     * @var UserFormatter
     */
    protected $formatter;

    /**
     * @param $data = [
     *     'protocal' => 'user.list'
     * ]
     */
    public function handle(Server $server, int $fd, $data)
    {
        // 查询所有在线的用户
        $mine = $this->service->find($fd);
        [$count, $users] = $this->userService->find($mine->id, ['is_online' => true]);

        $result = $this->formatter->list($users);

        $data['count'] = $count;
        $data['list'] = $result;
        $server->push($fd, json_encode($data));
    }
}
