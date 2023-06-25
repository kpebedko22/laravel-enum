<?php

namespace Kpebedko22\LaravelEnum\Tests;

use Kpebedko22\LaravelEnum\Tests\Enums\RoleEnum;
use Kpebedko22\LaravelEnum\Tests\Models\Example;

class EnumCastTest extends ApplicationTestCase
{
    public function test_can_set_model_value_using_enum_instance(): void
    {
        $model = new Example;
        $role = RoleEnum::find(RoleEnum::ADMIN);
        $model->role = $role;

        $this->assertEquals($role, $model->role);
    }

    public function test_can_create_model_using_enum_instance(): void
    {
        $role = RoleEnum::find(RoleEnum::ADMIN);
        $id = Example::create(['role' => $role])->id;
        $model = Example::find($id);

        $this->assertEquals($role, $model->role);
        $this->assertInstanceOf(RoleEnum::class, $model->role);
    }

    public function test_can_create_model_using_enum_key(): void
    {
        $roleKey = RoleEnum::ADMIN;
        $id = Example::create(['role' => $roleKey])->id;
        $model = Example::find($id);

        $this->assertEquals($roleKey, $model->role->key);
        $this->assertInstanceOf(RoleEnum::class, $model->role);
    }

    public function test_create_model_using_wrong_enum_key(): void
    {
        $id = Example::create(['role' => 'false-example'])->id;
        $model = Example::find($id);

        $this->assertNull($model->role);
    }

    public function test_model_get_changes_method_works_correctly(): void
    {
        $id = Example::create(['role' => RoleEnum::ADMIN])->id;

        $model = Example::find($id);

        $this->assertEquals($model->role, RoleEnum::find(RoleEnum::ADMIN));
        $this->assertEmpty($model->getChanges());

        $model->role = RoleEnum::ADMIN;
        $this->assertEmpty($model->getChanges());
        $model->save();

        $this->assertEquals($model->role, RoleEnum::find(RoleEnum::ADMIN));
        $this->assertEmpty($model->getChanges());
    }
}
