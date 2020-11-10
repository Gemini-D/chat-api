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
namespace HyperfTest\Cases;

use App\Service\UserMockeryService;
use HyperfTest\HttpTestCase;

/**
 * @internal
 * @coversNothing
 */
class UserMockeryServiceTest extends HttpTestCase
{
    public function testMockeryName()
    {
        $name = di()->get(UserMockeryService::class)->mockName();
        $this->assertTrue(in_array(mb_strlen($name), [2, 3]));
    }
}
