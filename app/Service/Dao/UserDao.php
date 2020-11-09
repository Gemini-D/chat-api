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
namespace App\Service\Dao;

use App\Model\User;
use App\Service\Service;

class UserDao extends Service
{
    /**
     * @param $id
     * @return null|User
     */
    public function first($id)
    {
        return User::findFromCache($id);
    }

    /**
     * @return User
     */
    public function firstByToken(string $token)
    {
        return User::query()->where('token', $token)->first();
    }

    /**
     * 返回所有在线的用户.
     * @return User[]
     */
    public function findOnline()
    {
        return User::query()->where('is_online', User::ONLINE)->get();
    }

    public function online($token, User $user = null, $status = User::ONLINE)
    {
        if ($user === null) {
            $user = $this->firstByToken($token);
        }

        if ($user) {
            $user->is_online = $status;
            $user->save();
        }
    }
}
