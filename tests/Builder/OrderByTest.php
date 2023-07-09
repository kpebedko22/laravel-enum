<?php

namespace Kpebedko22\Enum\Tests\Builder;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use Kpebedko22\Enum\Enum;
use Kpebedko22\Enum\Tests\Enums\RoleEnum;
use PHPUnit\Framework\TestCase;

final class OrderByTest extends TestCase
{
    protected const COLUMN = 'int_number';

    private function checkValues(Collection $values, bool $isAsc): void
    {
        $prevValue = $values->shift();

        foreach ($values as $curValue) {

            $isAsc
                ? $this->assertGreaterThanOrEqual($prevValue, $curValue)
                : $this->assertLessThanOrEqual($prevValue, $curValue);

            $prevValue = $curValue;
        }
    }

    public function test_using_asc_direction(): void
    {
        $values = RoleEnum::orderBy(self::COLUMN)
            ->pluck(self::COLUMN);

        $this->checkValues($values, true);
    }

    public function test_using_desc_direction(): void
    {
        $values = RoleEnum::orderBy(self::COLUMN, 'desc')
            ->pluck(self::COLUMN);

        $this->checkValues($values, false);
    }

    public function test_desc_function(): void
    {
        $orderByItems = RoleEnum::orderBy(self::COLUMN, 'desc')->get();
        $orderByDescItems = RoleEnum::orderByDesc(self::COLUMN)->get();

        $this->assertEquals($orderByItems, $orderByDescItems);
    }

    public function test_using_sorting_on_sample(): void
    {
        $builder = RoleEnum::where(['is_default' => true]);

        $ascValues = (clone $builder)->orderBy(self::COLUMN)->pluck(self::COLUMN);

        $this->checkValues($ascValues, true);

        $descValues = (clone $builder)->orderByDesc(self::COLUMN)->pluck(self::COLUMN);

        $this->checkValues($descValues, false);
    }

    public function test_multiple_orders(): void
    {
        $addColumn = 'is_default';

        $items = RoleEnum::orderBy(self::COLUMN)->orderBy($addColumn)->get();

        /** @var Enum $firstItem */
        $firstItem = $items->shift();

        $columnValue = $firstItem->getAttribute(self::COLUMN);
        $addColumnValue = $firstItem->getAttribute($addColumn);

        /** @var Enum $item */
        foreach ($items as $item) {
            $curValue = $item->getAttribute($addColumn);
            $curColumnValue = $item->getAttribute(self::COLUMN);

            $this->assertGreaterThanOrEqual($addColumnValue, $curValue);

            if ($addColumnValue != $curValue) {
                $columnValue = $curColumnValue;
            }

            $this->assertGreaterThanOrEqual($columnValue, $curColumnValue);

            $addColumnValue = $curValue;
        }
    }

    public function test_wrong_direction_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        RoleEnum::orderBy(self::COLUMN, 'undefined');
    }
}
