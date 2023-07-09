<?php

namespace Kpebedko22\Enum\Tests;

use InvalidArgumentException;
use Kpebedko22\Enum\Tests\Enums\RoleEnum;
use PHPUnit\Framework\TestCase;

final class EnumBuilderTest extends TestCase
{
    public function test_first_method_works_correctly(): void
    {
        $firstRole = RoleEnum::first();

        $this->assertInstanceOf(RoleEnum::class, $firstRole);
    }

    public function test_where_method_simple_mode(): void
    {
        $searchKey = RoleEnum::ADMIN;
        $collection = RoleEnum::where('key', '=', $searchKey)->get();

        $this->assertCount(1, $collection);

        $collection = RoleEnum::where('key', '=', $searchKey)
            ->where('is_default', '=', false)
            ->get();

        $this->assertCount(1, $collection);

        $collection = RoleEnum::where('key', '=', $searchKey)
            ->where('is_default', '=', true)
            ->get();

        $this->assertCount(0, $collection);
    }

//    public function test_where_method_short_mode(): void
//    {
//        $searching = RoleEnum::find(RoleEnum::ADMIN);
//
//        // TODO: not working
//        $collection = RoleEnum::where('key', $searching->key)->get();
//
//        $this->assertCount(1, $collection);
//        $this->assertContains($searching->key, $collection->pluck('key'));
//    }

    public function test_where_method_array_mode(): void
    {
        $searchKey = RoleEnum::ADMIN;

        $collection = RoleEnum::where(['key' => $searchKey])->get();

        $this->assertCount(1, $collection);

        $collection = RoleEnum::where([
            'key' => $searchKey,
            'is_default' => false,
        ])->get();

        $this->assertCount(1, $collection);

        $collection = RoleEnum::where([
            'key' => $searchKey,
            'is_default' => true,
        ])->get();

        $this->assertCount(0, $collection);
    }

//    public function test_where_method_operators(): void
//    {
//        $this->assertCount(
//            1,
//            RoleEnum::where('key', '=', RoleEnum::ADMIN)->get()
//        );
//
//        $this->assertCount(
//            2,
//            RoleEnum::where('key', '!=', RoleEnum::ADMIN)->get()
//        );
//
//        $this->assertCount(
//            2,
//            RoleEnum::where('key', '<>', RoleEnum::ADMIN)->get()
//        );
//
//        $this->assertCount(
//            3,
//            RoleEnum::where('int_number', '>', 0)->get()
//        );
//
//        $this->assertCount(
//            2,
//            RoleEnum::where('int_number', '>=', 62)->get()
//        );
//
//        $this->assertCount(
//            3,
//            RoleEnum::where('int_number', '<=', 62)->get()
//        );
//
//        $this->assertCount(
//            0,
//            RoleEnum::where('int_number', '<', 0)->get()
//        );
//    }

//    public function test_where_together_other_methods():void
//    {
//        $searchKey = RoleEnum::ADMIN;
//
//        RoleEnum::where('key', '=',$searchKey)
//            ->orWhere('is_default', '=', )
//    }

    public function test_random_function(): void
    {
        $allKeys = RoleEnum::availablePrimaryKeys();

        $item = RoleEnum::random();

        $this->assertInstanceOf(RoleEnum::class, $item);
        $this->assertContains($item->getKey(), $allKeys);

        $anotherItem = RoleEnum::whereNot(['key' => $item->getKey()])->random();

        $this->assertNotEquals($item->getKey(), $anotherItem->getKey());
    }
}
