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
namespace App\Service\Formatter;

use App\Model\User;
use App\Service\Service;
use Hyperf\Utils\Traits\StaticInstance;

class UserFormatter extends Service
{
    use StaticInstance;

    public function base(User $model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'is_online' => $model->is_online,
            'created_at' => $model->created_at->toDateTimeString(),
            'updated_at' => $model->updated_at->toDateTimeString(),
        ];
    }

    public function list($models, User $mine)
    {
        $result = [];
        foreach ($models as $model) {
            $item = $this->base($model);
            if ($model->id === $mine->id) {
                $item['own'] = true;
            }
            $result[] = $item;
        }

        return $result;
    }
}
