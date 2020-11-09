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
namespace App\Controller;

use App\Chat\Handler\ErrorMessageHandler;
use App\Chat\HandlerInterface;
use App\Chat\Node;
use App\Service\Formatter\UserFormatter;
use App\Service\UserData;
use App\Service\UserDataService;
use App\Service\UserServiceInterface;
use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Hyperf\Di\Annotation\Inject;
use Swoole\Http\Request;
use Swoole\Websocket\Frame;

class IndexController extends Controller implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{
    /**
     * @Inject
     * @var ErrorMessageHandler
     */
    protected $errorMessageHandler;

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

    public function onClose($server, int $fd, int $reactorId): void
    {
        if ($obj = $this->service->find($fd)) {
            $this->service->delete($obj);

            if ($user = $this->userService->firstByToken($obj->token)) {
                $this->userService->offline($user);
            }
        }
    }

    public function onMessage($response, Frame $frame): void
    {
        $data = json_decode($frame->data, true);

        $protocal = 'protocal.' . $data['protocal'] ?? '';
        if (! $this->container->has($protocal)) {
            $this->errorMessageHandler->handle($response, [
                'message' => 'The Protocal is invalid.',
            ]);
            return;
        }

        /** @var HandlerInterface $handler */
        $handler = $this->container->get($protocal);
        $handler->handle($response, $data);
    }

    public function onOpen($server, Request $request): void
    {
        $token = $this->request->input('token');

        $user = $this->userService->firstByToken($token);
        if (empty($user)) {
            $this->errorMessageHandler->handle($server, $request->fd, [
                'message' => 'The Token is invalid.',
                'close' => true,
            ]);
            return;
        }

        $this->userService->online($user);
        $node = di()->get(Node::class)->getId();

        $this->service->save(new UserData($user->id, $token, $request->fd, $node));

        [$count, $models] = $this->userService->find($user->id, ['is_online' => true]);
        $users = $this->formatter->list($models, $user);

        $result = [
            'protocal' => 'user.list',
            'count' => $count,
            'list' => $users,
        ];

        $server->push(json_encode($result));
    }
}
