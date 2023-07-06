<?php

namespace Kpebedko22\Enum\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use InvalidArgumentException;
use Kpebedko22\Enum\Enum;

class EnumKey implements ValidationRule
{
    public function __construct(protected string $enumClass)
    {
        if (!class_exists($this->enumClass)) {
            throw new InvalidArgumentException("Cannot validate against the enum, the class {$this->enumClass} doesn't exist.");
        }

        if (!is_a($this->enumClass, Enum::class, true)) {
            throw new InvalidArgumentException("The class {$this->enumClass} isn't Enum class.");
        }
    }

    protected function message(): string
    {
        return trans()->has('validation.enum_key')
            ? __('validation.enum_key')
            : __('enumPackage::validation.enum_key');
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->enumClass::isPrimaryKeyAvailable($value)) {
            $fail($this->message());
        }
    }
}
