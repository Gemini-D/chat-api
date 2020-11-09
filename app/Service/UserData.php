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
namespace App\Service;

class UserData
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $token;

    /**
     * @var int
     */
    public $fd;

    /**
     * @var string
     */
    public $node;

    public function __construct(int $id, string $token, int $fd, string $node)
    {
        $this->id = $id;
        $this->token = $token;
        $this->fd = $fd;
        $this->node = $node;
    }
}
