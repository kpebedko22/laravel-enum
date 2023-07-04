<?php

namespace Kpebedko22\Enum;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Kpebedko22\Enum\Exceptions\EnumNotFound;

class Builder
{
    protected array $primaryKeys;

    public function __construct(
        protected Enum  $enum,
        protected array $definition,
    )
    {
        $this->primaryKeys = array_column($this->definition, $enum->getPrimaryKey());
    }

    public function find(mixed $id): ?Enum
    {
        $primaryKey = $this->enum->getPrimaryKey();

        $items = array_filter(
            $this->getPreparedDefinition(),
            static fn(array $item) => $item[$primaryKey] == $id
        );

        if (count($items)) {
            $items = array_reverse($items);
            $data = array_pop($items);

            $clone = (clone $this->enum);
            $clone->fill($data);

            return $clone;
        }

        return null;
    }

    public function findOrFail(mixed $id): Enum
    {
        $item = $this->find($id);

        if (!$item) {
            throw new EnumNotFound(sprintf(
                "Enum item not found for [%s = %s] in [%s]",
                $this->enum->getPrimaryKey(), $id, get_class($this->enum),
            ));
        }

        return $item;
    }

    // all above is working fine...

    // TODO: in process
    public function where(string|array $column, ?string $operator = null, mixed $value = null, string $boolean = 'and'): static
    {
        if (is_array($column)) {

            foreach ($column as $name => $param) {
                $this->where($name, '=', $param, $boolean);
            }

        } elseif (is_string($column)) {

            $takingColumn = static fn($item) => Arr::get($item, $column);

            // TODO: take $column is not safe...
            $filterFunc = match ($operator) {
                '=' => static fn($item) => $takingColumn($item) == $value,
                '!=', '<>' => static fn($item) => $takingColumn($item) != $value,
                '>' => static fn($item) => $takingColumn($item) > $value,
                '>=' => static fn($item) => $takingColumn($item) >= $value,
                '<' => static fn($item) => $takingColumn($item) < $value,
                '<=' => static fn($item) => $takingColumn($item) <= $value,
                'like' => static fn($item) => stripos(__($takingColumn($item)), $value) !== false,
                'in' => static fn($item) => in_array($takingColumn($item), $value),
                'null' => static fn($item) => is_null($takingColumn($item)),
                'not_null' => static fn($item) => !is_null($takingColumn($item)),
                default => static fn($item) => true,
            };

            $res = array_filter($this->definition, $filterFunc);

            $this->updatePrimaryKeys(
                array_column($res, $this->enum->getPrimaryKey()),
                $boolean
            );
        }

        return $this;
    }

    public function orWhere(string|array $column, ?string $operator = null, mixed $value = null): static
    {
        return $this->where($column, $operator, $value, 'or');
    }

    public function whereIn(string $column, array $values, string $boolean = 'and'): static
    {
        return $this->where($column, 'in', $values, $boolean);
    }

    public function orWhereIn(string $column, array $values): static
    {
        return $this->where($column, 'in', $values, 'or');
    }

    public function whereNotIn(string $column, array $values, string $boolean = 'and'): static
    {
        return $this->where($column, 'in', $values, $boolean . ' not');
    }

    public function orWhereNotIn(string $column, array $values): static
    {
        return $this->where($column, 'in', $values, 'or not');
    }

    public function whereNot(string|array $column, ?string $operator = null, mixed $value = null, string $boolean = 'and'): static
    {
        return $this->where($column, $operator, $value, $boolean . ' not');
    }

    public function orWhereNot(string|array $column, ?string $operator = null, mixed $value = null): static
    {
        return $this->where($column, $operator, $value, 'or not');
    }

    public function whereNull(string|array $column, string $boolean = 'and', bool $not = false): static
    {
        $column = is_array($column)
            ? $column
            : [$column];

        $type = $not
            ? 'not_null'
            : 'null';

        foreach ($column as $item) {
            $this->where($item, $type, $boolean);
        }

        return $this;
    }

    public function orWhereNull(string|array $column): static
    {
        return $this->whereNull($column, 'or');
    }

    public function whereNotNull(string|array $column, string $boolean = 'and'): static
    {
        return $this->whereNull($column, $boolean, true);
    }

    public function orWhereNotNull(string|array $column): static
    {
        return $this->whereNull($column, 'or', true);
    }

    public function orderBy(string $column, string $direction = 'asc'): static
    {
        $direction = strtolower($direction);

        if (!in_array($direction, ['asc', 'desc'], true)) {
            throw new InvalidArgumentException('Order direction must be "asc" or "desc".');
        }

        $arr = $this->definition;

        usort($arr, static function ($a, $b) use ($column, $direction) {

            if ($a == $b) {
                return 0;
            }

            if ($direction === 'asc') {

                return ($a[$column] < $b[$column]) ? -1 : 1;
            }

            return ($a[$column] > $b[$column]) ? -1 : 1;
        });

        $keys = array_column($arr, $this->enum->getPrimaryKey());

        $this->orderPrimaryKeys($keys);

        return $this;
    }

    public function orderByDesc(string $column): static
    {
        return $this->orderBy($column, 'desc');
    }

    public function exists(): bool
    {
        return (bool)$this->count();
    }

    public function count(): int
    {
        return count($this->primaryKeys);
    }

    public function max(string $column): mixed
    {
        $values = array_column($this->getPreparedDefinition(), $column);

        $values = count($values)
            ? $values
            : [null];

        return max($values);
    }

    public function sum(string $column): float|int
    {
        return array_sum(array_column($this->getPreparedDefinition(), $column));
    }

    public function first(): Enum|null
    {
        $keys = array_reverse($this->primaryKeys);
        $key = array_pop($keys);

        return $this->find($key);
    }

    public function avg(string $column): float|int
    {
        $items = $this->getPreparedDefinition();

        $values = array_column($items, $column);

        $values = array_filter($values);

        return array_sum($values) / count($values);
    }

    public function average(string $column): float|int
    {
        return $this->avg($column);
    }

    public function random(): Enum
    {
        return $this->find(
            $this->primaryKeys[array_rand($this->primaryKeys)]
        );
    }

    public function get(): Collection
    {
        return (new Collection($this->primaryKeys))
            ->transform(function ($id) {
                return $this->find($id);
            });
    }

    public function pluck(mixed $value, mixed $key = null): Collection
    {
        return $this->get()->pluck($value, $key);
    }

    public function toOptionsArray(): array
    {
        return $this->get()
            ->mapWithKeys(static fn(Enum $enum) => $enum->toOption())
            ->toArray();
    }

    public function useOptionAttribute(?string $optionAttribute): static
    {
        $this->enum->setOptionAttribute($optionAttribute);

        return $this;
    }

    protected function updatePrimaryKeys(array $keys, string $boolean): void
    {
        $boolean = strtolower($boolean);

        $intersection = str_contains($boolean, 'and');
        $exclude = str_contains($boolean, 'not');

        if ($exclude) {
            $tmp = array_diff($this->primaryKeys, $keys);
        } else {
            $tmp = $intersection
                ? array_intersect($this->primaryKeys, $keys)
                : array_merge($this->primaryKeys, $keys);
        }

        $this->primaryKeys = array_unique($tmp);
    }

    protected function orderPrimaryKeys(array $keys): void
    {
        $this->primaryKeys = array_intersect($keys, $this->primaryKeys);
    }

    protected function getPreparedDefinition(): array
    {
        return array_filter($this->definition, function (array $item) {
            return in_array($item[$this->enum->getPrimaryKey()], $this->primaryKeys, true);
        });
    }
}
