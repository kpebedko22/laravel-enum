<?php

namespace Kpebedko22\Enum\Tests;

use Illuminate\Contracts\Console\Kernel;

/**
 * Generated enum classes are situated in
 * vendor/orchestra/testbench-core/laravel/app/Enums
 *
 * Commands run with --force flag to override existed classes
 */
final class EnumMakeCommandTest extends ApplicationTestCase
{
    protected const SUCCESSFUL_OUTPUT = 'created successfully';

    public function test_command_is_registered(): void
    {
        $consoleKernel = $this->app->make(Kernel::class);

        assert($consoleKernel instanceof Kernel);

        $commands = $consoleKernel->all();

        $this->assertArrayHasKey('make:enum', $commands);
    }

    public function test_command_simple_execution_is_successful(): void
    {
        $className = 'SimpleExecutionEnum';

        $this->artisan('make:enum', ['name' => $className, '--force' => true])
            ->expectsOutputToContain(self::SUCCESSFUL_OUTPUT)
            ->assertSuccessful();
    }

    public function test_command_wizard_execution_is_successful(): void
    {
        $examples = [
            [null, null, null, null],
            ['admin, manager', 'string', 'key', null],
            ['admin, manager', 'int', 'id', 'attr1, attr2'],
        ];

        foreach ($examples as $index => $example) {
            $this->wizardExecution(
                "WizardExecution{$index}Enum",
                $example[0],
                $example[1],
                $example[2],
                $example[3],
            );
        }
    }

    private function wizardExecution(
        string  $className,
        ?string $constants,
        ?string $constantType,
        ?string $primaryKey,
        ?string $fillable,
    ): void
    {
        $this->artisan("make:enum", ['name' => $className, '--wizard' => true, '--force' => true])
            ->expectsQuestion('Enter constants (separated by comma)', $constants)
            ->expectsChoice('Which type of constants?', $constantType, ['string', 'int'])
            ->expectsQuestion('Enter primary key name (one word)', $primaryKey)
            ->expectsQuestion('Enter additional fillable attributes (separated by comma)', $fillable)
            ->expectsOutputToContain(self::SUCCESSFUL_OUTPUT)
            ->assertSuccessful();
    }
}
