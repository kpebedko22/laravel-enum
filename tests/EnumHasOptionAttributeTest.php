<?php

namespace Kpebedko22\Enum\Tests;

use Kpebedko22\Enum\Exceptions\EnumOptionAttributeWrongType;
use Kpebedko22\Enum\Tests\Enums\ActionEnum;

final class EnumHasOptionAttributeTest extends ApplicationTestCase
{
    public function test_enum_via_reflection_works_correctly_for_builder()
    {
        $builder = ActionEnum::useOptionAttribute(null);
        $enumItem = (clone $builder)->first();

        $options = $builder->toOptionsArray();

        $numOptions = $builder->count();

        $this->assertIsArray($options);
        $this->assertCount($numOptions, $options);

        $optionsKeys = array_keys($options);
        $enumKeys = $builder->pluck($enumItem->getPrimaryKey())->toArray();

        $this->assertEquals($enumKeys, $optionsKeys);
        $this->assertContainsOnly('string', $options);
    }

    public function test_enum_via_reflection_different_languages_are_equals()
    {
        $builder = ActionEnum::useOptionAttribute(null);

        $this->app->setLocale(self::LOCALE_EN);
        $optionsEN = $builder->toOptionsArray();

        $this->app->setLocale(self::LOCALE_RU);
        $optionsRU = $builder->toOptionsArray();

        $this->assertEquals($optionsEN, $optionsRU);
    }

    public function test_enum_options_array_consist_of_strings()
    {
        $optionAttributes = [null, 'name', 'label'];

        foreach ($optionAttributes as $optionAttribute) {
            $builder = ActionEnum::useOptionAttribute($optionAttribute);
            $options = $builder->toOptionsArray();
            $this->assertContainsOnly('string', $options);
        }
    }

    public function test_doesnt_throw_exception_when_attribute_stringable()
    {
        $attributes = ['null', 'string', 'bool', 'int', 'float', 'stringable_class', 'undefined-label'];

        foreach ($attributes as $attribute) {
            ActionEnum::useOptionAttribute($attribute)->toOptionsArray();
        }

        $this->assertTrue(true);
    }

    public function test_throws_exception_when_attribute_is_object()
    {
        $this->expectException(EnumOptionAttributeWrongType::class);
        ActionEnum::useOptionAttribute('object')->toOptionsArray();
    }

    public function test_throws_exception_when_attribute_is_not_stringable_class()
    {
        $this->expectException(EnumOptionAttributeWrongType::class);
        ActionEnum::useOptionAttribute('class')->toOptionsArray();
    }

    public function test_enum_get_option_attribute_works_correctly()
    {
        $originalOptionAttribute = 'name';

        $enumItem = ActionEnum::useOptionAttribute($originalOptionAttribute)
            ->find(ActionEnum::VIEW);

        $optionAttribute = $enumItem->getOptionAttribute();

        $this->assertEquals($originalOptionAttribute, $optionAttribute);
    }
}
