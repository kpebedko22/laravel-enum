<?php

namespace Kpebedko22\Enum\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MakeEnumCommand extends GeneratorCommand
{
    protected const TYPE_INT = 'int';
    protected const TYPE_STR = 'string';

    protected $signature = 'make:enum {name} {--W|wizard} {--force}';

    protected $description = 'Create a new enum class';

    protected $type = 'Enum';

    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/enum.stub');
    }

    protected function resolveStubPath(string $stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return "{$rootNamespace}\Enums";
    }

    protected function buildClass($name): string
    {
        $stub = parent::buildClass($name);

        if ($this->option('wizard')) {

            $constants = $this->ask('Enter constants (separated by comma)');

            $constants = is_string($constants)
                ? collect((explode(',', $constants)))
                    ->map(fn(string $val) => trim($val))
                    ->map(fn(string $val) => str_replace(' ', '_', $val))
                    ->filter()
                    ->values()
                : collect(['example']);

            $type = $this->choice('Which type of constants?', [
                self::TYPE_STR,
                self::TYPE_INT,
            ], self::TYPE_STR);

            $type = is_null($type) ? self::TYPE_STR : $type;

            $primaryKey = $this->ask('Enter primary key name (one word)');
            $primaryKey = is_string($primaryKey) ? $primaryKey : 'key';

            $fillable = $this->ask('Enter additional fillable attributes (separated by comma)');
            $fillable = is_string($fillable)
                ? collect((explode(',', $fillable)))
                    ->map(fn(string $val) => trim($val))
                    ->map(fn(string $val) => str_replace(' ', '_', $val))
                    ->filter()
                    ->values()
                : collect();
        } else {

            $constants = collect(['example']);
            $primaryKey = 'key';
            $type = self::TYPE_STR;
            $fillable = collect();
        }

        $stub = $this->buildConstants($stub, $constants, $type);
        $stub = $this->buildPrimaryKey($stub, $primaryKey);
        $stub = $this->buildFillable($stub, $primaryKey, $fillable);
        $stub = $this->buildPhpDoc($stub, $primaryKey, $type, $fillable);
        $stub = $this->buildEnumDefinition($stub, $constants, $primaryKey, $fillable);

        return $stub;
    }

    protected function buildConstants(string $stub, Collection $constants, string $type): string
    {
        $constValue = match ($type) {
            self::TYPE_INT => static fn($value, $pos) => $pos + 1,
            default => static fn($value, $pos) => "'" . Str::lower($value) . "'",
        };

        $result = $constants
            ->map(function (string $const, string $index) use ($constValue) {
                $upper = Str::upper($const);
                $value = $constValue($const, $index);
                return "public const $upper = $value;";
            })
            ->implode("\n\t");

        return str_replace(
            'DummyConstants',
            $result,
            $stub
        );
    }

    protected function buildPrimaryKey(string $stub, string $primaryKey): string
    {
        return str_replace(
            'DummyPrimaryKey',
            $primaryKey,
            $stub
        );
    }

    protected function buildPhpDoc(string $stub, string $primaryKey, string $type, Collection $fillable): string
    {
        $params = $fillable
            ->mapWithKeys(fn(string $val) => [$val => 'string'])
            ->prepend($type, $primaryKey)
            ->map(fn(string $paramType, string $param) => " * @property $paramType \$$param")
            ->implode("\n");

        $phpDoc = "/**\n" . $params . "\n */";

        return str_replace(
            'DummyPhpDoc',
            $phpDoc,
            $stub
        );
    }

    protected function buildEnumDefinition(string $stub, Collection $constants, string $primaryKey, Collection $fillable): string
    {
        $res = $constants
            ->map(function (string $constant) use ($primaryKey, $fillable) {
                $constant = Str::upper($constant);

                return "[\n" .
                    "\t\t\t\t'$primaryKey' => self::$constant," .
                    $fillable->map(fn(string $val) => "\n\t\t\t\t'$val' => '',")->implode('') .
                    "\n\t\t\t],";
            })
            ->implode("\n\t\t\t");

        return str_replace(
            'DummyEnumDefinition',
            $res,
            $stub
        );
    }

    protected function buildFillable(string $stub, string $primaryKey, Collection $fillable): string
    {
        $params = (clone $fillable)
            ->prepend($primaryKey)
            ->map(fn(string $param) => "'$param',")
            ->implode("\n\t\t");

        return str_replace(
            'DummyFillable',
            $params,
            $stub
        );
    }
}
