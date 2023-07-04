<?php

namespace Kpebedko22\Enum\Concerns;

use Illuminate\Support\Str;
use Kpebedko22\Enum\Exceptions\EnumOptionAttributeWrongType;
use ReflectionClass;

trait HasOptionAttribute
{
    /**
     * Better change default value, to use specific column as label.
     * However, will be used constant beautified names.
     *
     * @var string|null
     */
    protected ?string $optionAttribute = null;

    public function toOption(): array
    {
        return [
            $this->getKey() => $this->getOptionAttributeValue(),
        ];
    }

    public function getOptionAttributeValue(): string
    {
        if (is_null($this->optionAttribute)) {

            $class = new ReflectionClass(static::class);

            $constantName = array_search($this->getKey(), $class->getConstants());

            return Str::headline(Str::lower($constantName));
        }

        $value = $this->getAttribute($this->optionAttribute);

        $canCastToString = (!is_array($value)) && (
                (!is_object($value) && settype($value, 'string') !== false) ||
                (is_object($value) && method_exists($value, '__toString'))
            );

        if (!$canCastToString) {
            throw new EnumOptionAttributeWrongType();
        }

        return strval($value);
    }

    public function getOptionAttribute(): ?string
    {
        return $this->optionAttribute;
    }

    public function setOptionAttribute(?string $optionAttribute = null): static
    {
        $this->optionAttribute = $optionAttribute;

        return $this;
    }
}
