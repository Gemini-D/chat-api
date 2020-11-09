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
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;

/**
 * 根据实际情况修改.
 */
class UserMockeryService implements UserServiceInterface
{
    /**
     * @Inject
     * @var Redis
     */
    protected $redis;

    public function first(string $token): ?User
    {
        $res = $this->redis->get($token);
        if (! $res) {
            $res = $this->mock($token);
            $this->redis->set($token, $res, 86400);
        }

        return $this->fill($res);
    }

    public function find(string $token, array $search = [], int $offset = 0, int $limit = 10): array
    {
    }

    public function mock(string $token): array
    {
        return [
            'id' => (int) $this->redis->incr('mock:user:id'),
            'name' => $this->mockName(),
            'token' => $token,
        ];
    }

    public function fill(array $items): User
    {
        return (new User())->newFromBuilder($items);
    }

    public function mockName(): string
    {
        $string = '君不见黄河之水天上来奔流到海不复回君不见高堂明镜悲白发朝如青丝暮成雪人生得意须尽欢莫使金樽空对月天生我材必有用千金散尽还复来烹羊宰牛且为乐会须一饮三百杯岑夫子丹丘生将进酒杯莫停与君歌一曲请君为我倾耳听钟鼓馔玉不足贵但愿长醉不愿醒古来圣贤皆寂寞惟有饮者留其名陈王昔时宴平乐斗酒十千恣欢谑主人何为言少钱径须沽取对君酌五花马千金裘呼儿将出换美酒与尔同销万古愁';
        $length = strlen($string) / 3;
        $i = rand(0, $length - 3);
        $res = $string[$i * 3] . $string[$i * 3 + 1] . $string[$i * 3 + 2];
        $i = rand(0, $length - 3);
        $res .= $string[$i * 3] . $string[$i * 3 + 1] . $string[$i * 3 + 2];
        if (rand(0, 9) > 4) {
            $i = rand(0, $length - 3);
            $res .= $string[$i * 3] . $string[$i * 3 + 1] . $string[$i * 3 + 2];
        }

        return $res;
    }
}
