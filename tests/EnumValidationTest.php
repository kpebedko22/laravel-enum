<?php

namespace Kpebedko22\Enum\Tests;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Kpebedko22\Enum\Rules\EnumKey;
use Kpebedko22\Enum\Tests\Enums\ActionEnum;
use Kpebedko22\Enum\Tests\Models\Example;

final class EnumValidationTest extends ApplicationTestCase
{
    public function test_enum_key_rule_passes_successfully(): void
    {
        $validator = Validator::make(
            ['status' => ActionEnum::VIEW],
            ['status' => [new EnumKey(ActionEnum::class)]],
        );

        $this->assertTrue($validator->passes());
    }

    public function test_enum_key_rule_fails_on_null_value(): void
    {
        $validator = Validator::make(
            ['status' => null],
            ['status' => [new EnumKey(ActionEnum::class)]],
        );

        $this->assertTrue($validator->fails());
    }

    public function test_enum_key_rule_message_can_be_overridden(): void
    {
        $this->app->setLocale(self::LOCALE_RU);

        $validator = Validator::make(
            ['status' => null],
            ['status' => [new EnumKey(ActionEnum::class)]],
        );

        $this->assertTrue($validator->fails());

        $message = $validator->messages()->get('status')[0];

        $translatedMessage = trans('validation.enum_key');

        $this->assertEquals($message, $translatedMessage);
    }

    public function test_enum_key_rule_passes_on_null_value_alongside_nullable_rule(): void
    {
        $validator = Validator::make(
            ['status' => null],
            ['status' => ['nullable', new EnumKey(ActionEnum::class)]],
        );

        $this->assertTrue($validator->passes());
    }

    public function test_enum_key_constructor_fails_using_undefined_class(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new EnumKey('not-a-real-class');
    }

    public function test_enum_key_constructor_fails_using_not_enum_class(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new EnumKey(Example::class);
    }
}
