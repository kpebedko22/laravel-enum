<?php

namespace Kpebedko22\Enum;

use Illuminate\Support\ServiceProvider;
use Kpebedko22\Enum\Commands\MakeEnumCommand;

class EnumServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootCommands();
        $this->bootTranslations();
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
