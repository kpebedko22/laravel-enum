<?php

namespace Kpebedko22\LaravelEnum;

use ArrayAccess;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\MissingAttributeException;
use Illuminate\Support\Collection;
use Kpebedko22\LaravelEnum\Casts\EnumCast;
use Kpebedko22\LaravelEnum\Concerns\HasAttributes;
use Kpebedko22\LaravelEnum\Concerns\HasLanguage;
use Kpebedko22\LaravelEnum\Concerns\Selectable;

/**
 * @method static static|null find(mixed $id)
 * @method static static findOrFail(mixed $id)
 * @method static Collection get()
 * @method static Collection pluck(mixed $value, mixed $key = null)
 * @method static Builder where(string|array $column, ?string $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder orWhere(string|array $column, ?string $operator = null, mixed $value = null)
 * @method static Builder whereIn(string $column, array $values, string $boolean = 'and')
 * @method static Builder orWhereIn(string $column, array $values)
 * @method static Builder whereNotIn(string $column, array $values, string $boolean = 'and')
 * @method static Builder orWhereNotIn(string $column, array $values)
 * @method static Builder whereNot(string|array $column, ?string $operator = null, mixed $value = null, string $boolean = 'and')
 * @method static Builder orWhereNot(string|array $column, ?string $operator = null, mixed $value = null)
 * @method static Builder whereNull(string|array $column, string $boolean = 'and', bool $not = false)
 * @method static Builder orWhereNull(string|array $column)
 * @method static Builder whereNotNull(string|array $column, string $boolean = 'and')
 * @method static Builder orWhereNotNull(string|array $column)
 * @method static Builder orderBy(string $column, string $direction = 'asc')
 * @method static Builder orderByDesc(string $column)
 * @method static bool exists()
 * @method static int count()
 * @method static mixed max(string $column)
 * @method static float|int sum(string $column)
 * @method static static|null first()
 * @method static float|int avg(string $column)
 * @method static float|int average(string $column)
 * @method static static random()
 */
abstract class Enum implements Arrayable, ArrayAccess, Castable
{
    use HasAttributes,
        HasLanguage,
        Selectable;

    protected string $primaryKey = 'id';

    protected array $fillable = [];

    abstract protected static function getEnumDefinition(): array;

    protected function __construct(array $data = [])
    {
        $this->fill($data);
    }

    public function getFillable(): array
    {
        return $this->fillable;
    }

    protected function isFillable(string $key): bool
    {
        if (in_array($key, $this->getFillable(), true)) {
            return true;
        }

        return false;
    }

    public static function all(): Collection
    {
        return static::query()->get();
    }

    public function fill(array $attributes): void
    {
        foreach ($attributes as $key => $value) {
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            }
        }
    }

    public function is(mixed $value): bool
    {
        if ($value instanceof static) {
            return $this->getKey() === $value->getKey();
        }

        return $value === $this->getKey();
    }

    public function isNot(mixed $value): bool
    {
        return !$this->is($value);
    }

    public function toArray(): array
    {
        return $this->attributesToArray();
    }

    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    public function getKey()
    {
        return $this->getAttribute($this->getPrimaryKey());
    }

    public function __get(string $key)
    {
        return $this->getAttribute($key);
    }

    public function __set(string $key, mixed $value)
    {
        $this->setAttribute($key, $value);
    }

    public static function isPrimaryKeyAvailable(mixed $id): bool
    {
        $keys = static::availablePrimaryKeys();

        return in_array($id, $keys, true);
    }

    public static function availablePrimaryKeys(): array
    {
        $definition = static::getEnumDefinition();
        $primaryKey = (new static)->getPrimaryKey();

        return array_map(
            static fn(array $item) => $item[$primaryKey],
            $definition
        );
    }

    public static function parseDatabase(mixed $value): mixed
    {
        return $value;
    }

    public static function serializeDatabase(mixed $value): mixed
    {
        if ($value instanceof self) {
            return $value->getKey();
        }

        return $value;
    }

    public static function castUsing(array $arguments): EnumCast
    {
        return new EnumCast(static::class);
    }

    public static function query(): Builder
    {
        return (new static)->newQuery();
    }

    public function newQuery(): Builder
    {
        return new Builder(
            new static,
            static::getEnumDefinition(),
        );
    }

    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }

    public function __call($method, $parameters)
    {
        return $this->newQuery()->{$method}(...$parameters);
    }

    public function offsetExists(mixed $offset): bool
    {
        try {
            return !is_null($this->getAttribute($offset));
        } catch (MissingAttributeException) {
            return false;
        }
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->getAttribute($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->setAttribute($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->attributes[$offset]);
    }
}
