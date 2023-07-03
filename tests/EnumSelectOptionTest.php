<?php

namespace Kpebedko22\Enum\Tests;

use Kpebedko22\Enum\Tests\Enums\ActionEnum;
use Kpebedko22\Enum\Tests\Enums\RoleEnum;

final class EnumSelectOptionTest extends ApplicationTestCase
{
    const LOCALE_EN = 'en';
    const LOCALE_RU = 'ru';

    protected function getEnumWithReflectionOptionLabel(): ActionEnum
    {
        return new class extends ActionEnum {
            protected ?string $optionLabel = null;
        };
    }

    public function test_enum_via_reflection_works_correctly_for_builder()
    {
        $enum = $this->getEnumWithReflectionOptionLabel();

        $options = $enum::toOptionsArray();
    }

    public function test_enum_via_reflection_works_correctly_for_one_item()
    {

    }

    // 1. работает обычный вызов
    // 1.1 (null) при разных языках возвращает одно и то же
    // 1.2 (any) возвращает массив
    // 1.3 (any) работает с билдером

    // 2. работает для айтема
    // 2.1 (null) при разных языках возвращает одно и то же
    // 2.2 (any) возвращает строку

    // 3. (special) кинет эксепшен если задать не строку / не существующий аксессор/fillable как optionLabel

    public function test_options_keys_are_enum_primary_keys(): void
    {
        $keys = RoleEnum::availablePrimaryKeys();

        $options = RoleEnum::toOptionsArray();
        $optionsKeys = array_keys($options);

        $this->assertSame($optionsKeys, $keys);
    }

    public function test_option_label_via_reflection_constants(): void
    {
        $class = $this->getEnumWithReflectionOptionLabel();

        $options = $class::toOptionsArray();
        $count = $class::count();
        $this->assertCount($count, $options);

        $firstAction = $class::first();
        $firstActionLabel = $firstAction->getOptionLabel();
        $this->assertIsString($firstActionLabel);
        // 1. работает у одного айтема
        // 2. работает для всего енама
        //
    }

    public function test_option_label_via_attribute(): void
    {
        $class = new class extends ActionEnum {
            protected ?string $optionLabel = 'label';
        };

        $options = $class::toOptionsArray();
    }

    public function test_option_label_via_accessor(): void
    {
        $class = new class extends ActionEnum {
            protected ?string $optionLabel = 'name';

            public function getNameAttribute(): string
            {
                return "enum/action.$this->key";
            }
        };

        $options = $class::toOptionsArray();
    }

    public function test_simple_select_options(): void
    {
        $this->app->setLocale(self::LOCALE_EN);
        $optionsEN = RoleEnum::toOptionsArray();

        $this->assertIsArray($optionsEN);
        $this->assertContains('Admin', $optionsEN);
        $this->assertContains('Manager', $optionsEN);
        $this->assertContains('Usual', $optionsEN);

        $this->app->setLocale(self::LOCALE_RU);
        $optionsRU = RoleEnum::toOptionsArray();

        $this->assertEquals($optionsEN, $optionsRU);
    }

    public function test_can_localize_select_options(): void
    {
        $this->app->setLocale(self::LOCALE_RU);
        $optionsRU = ActionEnum::toOptionsArray();

        $this->assertContains('Просмотр', $optionsRU);
        $this->assertContains('Создание', $optionsRU);
        $this->assertContains('Редактирование', $optionsRU);
        $this->assertContains('Удаление', $optionsRU);
    }

    public function test_convert_to_certain_options(): void
    {
        $builder = ActionEnum::where(['is_available_to_everyone' => true]);
        $count = (clone $builder)->count();
        $optionsWhereTrue = (clone $builder)->toOptionsArray();

        $this->assertIsArray($optionsWhereTrue);
        $this->assertCount($count, $optionsWhereTrue);

        $builder = ActionEnum::where(['is_available_to_everyone' => false]);
        $count = (clone $builder)->count();
        $optionsWhereFalse = (clone $builder)->toOptionsArray();

        $this->assertIsArray($optionsWhereFalse);
        $this->assertCount($count, $optionsWhereFalse);
        $this->assertNotSame($optionsWhereTrue, $optionsWhereFalse);
    }
}
