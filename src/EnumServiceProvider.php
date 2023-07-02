<?php

namespace Kpebedko22\Enum;

use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Support\ServiceProvider;
use Kpebedko22\Enum\Commands\MakeEnumCommand;
use Kpebedko22\Enum\Rules\EnumKey;

class EnumServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootCommands();
        $this->bootTranslations();
        $this->bootValidators();
    }

    protected function bootCommands(): void
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/Commands/stubs' => $this->app->basePath('stubs')
            ], 'enum-package-stubs');

            $this->commands([
                MakeEnumCommand::class,
            ]);
        }
    }

    protected function bootValidators(): void
    {
        /** @var ValidationFactory $validationFactory */
        $validationFactory = $this->app->make(ValidationFactory::class);

        $validationFactory->extend('enum_key', function ($attribute, $value, $parameters, $validator) {
            $enum = $parameters[0] ?? null;

            return (new EnumKey($enum))->passes($attribute, $value);
        }, __('enumPackage::validation.enum_key'));
    }

    protected function bootTranslations(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '../lang' => lang_path('vendor/enumPackage'),
            ], 'enum-package-translations');
        }

        $this->loadTranslationsFrom(__DIR__ . '../lang', 'enumPackage');
    }
}
