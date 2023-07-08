<?php

namespace Kpebedko22\Enum\Tests;

use Kpebedko22\Enum\Tests\Enums\ActionEnum;
use PHPUnit\Framework\TestCase;

final class EnumArrayAccessTest extends TestCase
{
    public function test_attribute_is_accessed(): void
    {
        $item = ActionEnum::first();

        $this->assertEquals($item->key, $item['key']);
    }

    public function test_not_existed_attribute_is_null(): void
    {
        $item = ActionEnum::first();

        $this->assertNull($item['not-existed-attribute']);
    }

    public function test_attribute_can_be_set(): void
    {
        $item = ActionEnum::first();

        $valueBefore = $item->label;

        $item['label'] = 'New value';

        $valueAfter = $item->label;

        $this->assertNotEquals($valueBefore, $valueAfter);
        $this->assertEquals('New value', $valueAfter);
    }

    public function test_not_existed_attribute_can_be_set(): void
    {
        $item = ActionEnum::first();

        $valueBefore = $item->not_existed_attr;

        $this->assertNull($valueBefore);

        $item['not_existed_attr'] = 'New value';

        $valueAfter = $item->not_existed_attr;

        $this->assertEquals('New value', $valueAfter);
    }

    public function test_attribute_can_be_unset(): void
    {
        $item = ActionEnum::first();

        unset($item['label']);

        $attributes = $item->getAttributes();
        $attributes = array_keys($attributes);

        $this->assertNotContains('label', $attributes);
    }

    public function test_not_existed_attribute_unsetting_does_not_fail(): void
    {
        $item = ActionEnum::first();

        $attributes = $item->getAttributes();
        $attributes = array_keys($attributes);

        unset($item['not-existed-attribute']);

        $attributesAfterUnset = $item->getAttributes();
        $attributesAfterUnset = array_keys($attributesAfterUnset);

        $this->assertEquals($attributes, $attributesAfterUnset);
    }
}
