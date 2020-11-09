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
use Swoole\Http\Response;

class ErrorMessageHandler implements HandlerInterface
{
    /**
     * @param $data = [
     *     'message' => '错误信息',
     *     'close' => false, // 是否前置关闭客户端
     * ]
     */
    public function handle(Response $server, $data)
    {
        $server->push(json_encode($data));

        if ($data['close'] ?? false) {
            $server->close();
        }
    }
}
