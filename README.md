# Enum Package

## Installation

```shell
composer require kpebedko22/laravel-enum
```

## Commands

### Make Enum Command

There is a command for creating enum classes in `app\Enums\` folder:

```shell
php artisan make:enum {name} {--Q|questionable}
```

For example:

```shell
php artisan make:enum TestEnum -Q
```

Option `-Q` is responsible for questions during creating enum class. User will be asked for:

- Constants (separated by comma)
- Primary key type (int / string)
- Primary key name (e.g. 'id', 'key')
- Fillable attributes (separated by comma)

### Publishing stub

`php artisan vendor:publish --tag=enum-package-stubs`

## Publishing translations

If you wish to translate the package, you may publish the language files using:

`php artisan vendor:publish --tag=enum-package-translations`


