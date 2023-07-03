<?php

namespace Kpebedko22\Enum\Concerns;

use Illuminate\Support\Str;
use Kpebedko22\Enum\Exceptions\EnumOptionLabelWrongType;
use ReflectionClass;

trait HasOptionLabel
{
    /**
     * Better change default value, to use specific column as label.
     * However, will be used constant beautified names.
     *
     * @var string|null
     */
    protected ?string $optionLabel = null;

    public function toOption(): array
    {
        return [
            $this->getKey() => $this->getOptionLabel(),
        ];
    }

    public function getOptionLabel(): string
    {
        if (is_null($this->optionLabel)) {

            $class = new ReflectionClass(static::class);

            $constantName = array_search($this->getKey(), $class->getConstants());

            return Str::headline(Str::lower($constantName));
        }

        $optionLabel = $this->getAttribute($this->optionLabel);

        if (!is_string($optionLabel)) {
            throw new EnumOptionLabelWrongType();
        }

        return $optionLabel;
    }
}
