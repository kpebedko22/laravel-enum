<?php

namespace Kpebedko22\Enum\Rules;

use Illuminate\Contracts\Validation\Rule;
use InvalidArgumentException;
use Kpebedko22\Enum\Enum;

class EnumKey implements Rule
{
    protected string $rule = 'enum_key';

    public function __construct(
        protected string $enumClass
    )
    {
        if (!class_exists($this->enumClass)) {
            throw new InvalidArgumentException("Cannot validate against the enum, the class {$this->enumClass} doesn't exist.");
        }

        if (!is_a($this->enumClass, Enum::class, true)) {
            throw new InvalidArgumentException("The class {$this->enumClass} isn't Enum class.");
        }
    }

    public function passes($attribute, $value): bool
    {
        return $this->enumClass::isPrimaryKeyAvailable($value);
    }

    public function message(): string
    {
        return trans()->has('validation.enum_key')
            ? __('validation.enum_key')
            : __('enumPackage::validation.enum_key');
    }

    public function __toString(): string
    {
        return "{$this->rule}:{$this->enumClass}";
    }
}
