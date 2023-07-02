<?php

namespace Kpebedko22\Enum\Tests;

use Kpebedko22\Enum\Tests\Enums\RoleEnum;
use PHPUnit\Framework\TestCase;

final class EnumComparisonTest extends TestCase
{
    public function test_comparison_plain_value_matching(): void
    {
        $admin = RoleEnum::find(RoleEnum::ADMIN);

        $this->assertTrue($admin->is(RoleEnum::ADMIN));
        $this->assertTrue($admin->is('admin'));
    }

    public function test_comparison_plain_value_not_matching(): void
    {
        $admin = RoleEnum::find(RoleEnum::ADMIN);

        $this->assertFalse($admin->is(RoleEnum::MANAGER));
        $this->assertFalse($admin->is('false-value'));
        $this->assertTrue($admin->isNot(RoleEnum::MANAGER));
        $this->assertTrue($admin->isNot('false-value'));
    }

    public function test_comparison_itself_matching(): void
    {
        $admin = RoleEnum::find(RoleEnum::ADMIN);

        $this->assertTrue($admin->is($admin));
    }

    public function test_comparison_other_instance_matching(): void
    {
        $admin = RoleEnum::find(RoleEnum::ADMIN);
        $otherAdmin = RoleEnum::find(RoleEnum::ADMIN);

        $this->assertTrue($admin->is($otherAdmin));
        $this->assertTrue($otherAdmin->is($admin));
    }

    public function test_comparison_other_instance_not_matching(): void
    {
        $admin = RoleEnum::find(RoleEnum::ADMIN);
        $manager = RoleEnum::find(RoleEnum::MANAGER);

        $this->assertFalse($admin->is($manager));
        $this->assertFalse($manager->is($admin));
        $this->assertTrue($admin->isNot($manager));
        $this->assertTrue($manager->isNot($admin));
    }

    public function test_comparison_mixed_value_not_matching(): void
    {
        $admin = RoleEnum::find(RoleEnum::ADMIN);

        $this->assertFalse($admin->is(1));
        $this->assertFalse($admin->is(10.5));
        $this->assertFalse($admin->is('string'));
        $this->assertFalse($admin->is([1, 2, 3]));
        $this->assertFalse($admin->is(true));
        $this->assertFalse($admin->is(null));
        $this->assertFalse($admin->is((object)['test' => 'string']));
        $this->assertFalse($admin->is(fn() => 10));
        $this->assertFalse($admin->is(fn() => 'admin'));
    }
}
