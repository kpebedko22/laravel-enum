<?php

namespace Kpebedko22\LaravelEnum\Casts;

use Kpebedko22\LaravelEnum\Enum;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class EnumCast implements CastsAttributes
{
    public function __construct(
        protected string $enumClass
    )
    {
    }

    public function get($model, string $key, $value, array $attributes)
    {
        return $this->castEnum($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        $value = $this->castEnum($value);

        $value = call_user_func([$this->enumClass, 'serializeDatabase'], $value);

        return [$key => $value];
    }

    protected function castEnum(mixed $value): ?Enum
    {
        if ($value === null || $value instanceof $this->enumClass) {
            return $value;
        }

        $value = $this->getCastableValue($value);

        if ($value === null) {
            return null;
        }

        return call_user_func([$this->enumClass, 'find'], $value);
    }

    protected function getCastableValue(mixed $value): mixed
    {
        // If the enum has overridden the 'parseDatabase' method, use it to get the cast value
        $value = call_user_func([$this->enumClass, 'parseDatabase'], $value);

        if ($value === null) {
            return null;
        }

        // If the value exists in the enum (using strict type checking) return it
        if (call_user_func([$this->enumClass, 'isPrimaryKeyAvailable'], $value)) {
            return $value;
        }

        // Find the value in the enum that the incoming value can be coerced to
        $primaryKeys = call_user_func([$this->enumClass, 'availablePrimaryKeys']);

        foreach ($primaryKeys as $enumValue) {
            if ($value == $enumValue) {
                return $enumValue;
            }
        }

        // Fall back to trying to construct it directly (will result in an error since it doesn't exist)
        return $value;
    }
}
