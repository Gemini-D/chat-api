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
namespace App\Service\Redis;

use App\Service\UserData;
use Xin\RedisCollection\StringCollection;

class UserCollection extends StringCollection
{
    /**
     * @var string
     */
    protected $prefix = 'user:collection:';

    public function redis()
    {
        return di()->get(\Redis::class);
    }

    public function save(UserData $obj)
    {
        $str = serialize($obj);

        return $this->set($obj->id, $str, null);
    }

    public function find(int $id): ?UserData
    {
        $str = $this->get($id);

        if ($str) {
            return unserialize($str);
        }

        return null;
    }
}
