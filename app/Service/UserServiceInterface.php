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

use App\Model\User;

interface UserServiceInterface
{
    /**
     * 查询某人的基本信息.
     */
    public function first(string $token): ?User;

    /**
     * 查询某人的好友列表.
     * @param $search = [
     *     'is_online' => true,
     * ]
     * @return User[]
     */
    public function find(string $token, array $search = [], int $offset = 0, int $limit = 10): array;
}
