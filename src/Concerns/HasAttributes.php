<?php

namespace Kpebedko22\Enum\Concerns;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;

trait HasAttributes
{
    protected array $attributes = [];

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute(string $key)
    {
        if (array_key_exists($key, $this->attributes) || $this->hasGetMutator($key)) {
            return $this->transformModelValue($key, $this->getAttributeFromArray($key));
        }

        return null;
    }

    protected function getAttributeFromArray(string $key): mixed
    {
        return $this->getAttributes()[$key] ?? null;
    }

    protected function transformModelValue(string $key, mixed $value)
    {
        if ($this->hasGetMutator($key)) {
            return $this->mutateAttribute($key, $value);
        }

        return $value;
    }

    public function setAttribute(string $key, mixed $value)
    {
        if ($this->hasSetMutator($key)) {
            return $this->setMutatedAttributeValue($key, $value);
        }

        $this->attributes[$key] = $value;

        return $this;
    }

    public function attributesToArray(): array
    {
        $attributes = $this->attributes;

        foreach ($attributes as $key => $value) {

            if ($this->hasGetMutator($key)) {
                $value = $this->mutateAttributeForArray($key, $value);
            }

            $attributes[$key] = $value;
        }

        return $attributes;
    }

    protected function mutateAttributeForArray($key, $value)
    {
        $value = $this->mutateAttribute($key, $value);

        return $value instanceof Arrayable
            ? $value->toArray()
            : $value;
    }

    protected function mutateAttribute(string $key, mixed $value): mixed
    {
        return $this->{'get' . Str::studly($key) . 'Attribute'}($value);
    }

    protected function setMutatedAttributeValue(string $key, mixed $value): mixed
    {
        return $this->{'set' . Str::studly($key) . 'Attribute'}($value);
    }

    protected function hasGetMutator(string $key): bool
    {
        return method_exists($this, 'get' . Str::studly($key) . 'Attribute');
    }

    protected function hasSetMutator(string $key): bool
    {
        return method_exists($this, 'set' . Str::studly($key) . 'Attribute');
    }
}
