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
namespace App\Chat;

use Swoole\Http\Response;

interface HandlerInterface
{
    /**
     * @param $data
     */
    public function handle(Response $server, $data);
}
