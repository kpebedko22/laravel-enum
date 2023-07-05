<?php

namespace Kpebedko22\Enum\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Kpebedko22\Enum\Tests\Enums\RoleEnum;
use Kpebedko22\Enum\Tests\Enums\StatusEnum;

/**
 * @property int $id
 * @property RoleEnum|null $role
 * @property StatusEnum|null $status
 *
 * @method static Example create($attributes)
 * @method static Example|null find($id)
 */
class Example extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'role' => RoleEnum::class,
        'status' => StatusEnum::class,
    ];
}
