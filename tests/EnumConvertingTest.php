<?php

namespace Kpebedko22\Enum\Tests;

use Kpebedko22\Enum\Tests\Enums\RoleEnum;
use Kpebedko22\Enum\Tests\Enums\StatusEnum;
use PHPUnit\Framework\TestCase;

final class EnumConvertingTest extends TestCase
{
    public function test_converting_to_string(): void
    {
        $role = RoleEnum::find(RoleEnum::ADMIN);
        $status = StatusEnum::find(StatusEnum::NEW);

        $stringRole = (string)$role;
        $stringStatus = (string)$status;

        $this->assertIsString($stringRole);
        $this->assertEquals(RoleEnum::ADMIN, $stringRole);

        $this->assertIsString($stringStatus);
        $this->assertEquals(StatusEnum::NEW, $stringStatus);
    }

    public function test_converting_to_boolean(): void
    {
        $enum = RoleEnum::find(RoleEnum::ADMIN);

        $this->assertTrue((bool)$enum);
    }

    public function test_converting_to_array(): void
    {
        $enum = RoleEnum::find(RoleEnum::ADMIN);

        $this->assertIsArray($enum->toArray());
        $this->assertIsArray((array)$enum);
    }
}
