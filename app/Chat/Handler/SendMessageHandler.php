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

use App\Chat\Constants;
use App\Chat\HandlerInterface;
use App\Service\Dao\UserDao;
use App\Service\UserDataService;
use App\Service\UserServiceInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Nsq\Nsq;
use Hyperf\Utils\Codec\Json;
use Hyperf\WebSocketServer\Sender;
use Swoole\WebSocket\Server;

class SendMessageHandler implements HandlerInterface
{
    /**
     * @Inject
     * @var UserDao
     */
    protected $dao;

    /**
     * @Inject
     * @var UserDataService
     */
    protected $service;

    /**
     * @Inject
     * @var Sender
     */
    protected $sender;

    /**
     * @Inject
     * @var Nsq
     */
    protected $nsq;

    /**
     * @Inject
     * @var ErrorMessageHandler
     */
    protected $errorHandler;

    /**
     * @Inject
     * @var UserServiceInterface
     */
    protected $userService;

    /**
     * @param $data = [
     *     'protocal' => 'send.message',
     *     'data' => [
     *         'id' => 1, // 目标ID
     *         'message' => 'Hello World.'
     *     ]
     * ]
     */
    public function handle(Server $server, int $fd, $data)
    {
        $id = $data['data']['id'] ?? 0;
        $message = $data['data']['message'] ?? null;

        if ($id && ! is_null($message)) {
            $user = $this->userService->first($id);
            if (empty($user)) {
                $this->errorHandler->handle($server, $fd, [
                    'message' => '目标用户不存在',
                ]);
                return;
            }

            $this->nsq->publish(Constants::SEND_MESSAGE, Json::encode([
                'data' => $data,
            ]));
        }
    }
}
