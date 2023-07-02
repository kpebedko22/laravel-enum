<?php

namespace Kpebedko22\Enum\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Kpebedko22\Enum\Enum;
use ReflectionClass;

trait Selectable
{
    /**
     * Better change default value, to use specific column as label.
     * However, will be used constant beautified names.
     *
     * @var string|null
     */
    protected ?string $optionLabel = null;

    public static function selectOptions(): array
    {
        /** @var Collection $collection */
        $collection = static::all();

        $options = $collection->mapWithKeys(fn(Enum $enum) => $enum->toSelectOption());

        return $options->toArray();
    }

    protected function toSelectOption(): array
    {
        return [
            $this->getKey() => $this->getOptionLabel(),
        ];
    }

    protected function getOptionLabel(): mixed
    {
        if (is_null($this->optionLabel)) {

            $class = new ReflectionClass(static::class);

            $constantName = array_search($this->getKey(), $class->getConstants());

            return Str::headline(Str::lower($constantName));
        }

        return $this->getAttribute($this->optionLabel);
    }
}
