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

use App\Service\Redis\UserCollection;
use Hyperf\Di\Annotation\Inject;

class UserDataService extends Service
{
    /**
     * @var UserData[]
     */
    public $users = [];

    /**
     * @Inject
     * @var UserCollection
     */
    protected $col;

    public function save(UserData $obj)
    {
        $this->users[$obj->fd] = $obj;

        $this->col->save($obj);
    }

    public function find(int $fd): ?UserData
    {
        return $this->users[$fd] ?? null;
    }

    public function delete(UserData $obj)
    {
        unset($this->users[$obj->fd]);

        $this->col->delete($obj->token);
    }
}
