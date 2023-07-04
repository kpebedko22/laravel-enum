<?php

namespace Kpebedko22\Enum\Tests\Enums;

use Kpebedko22\Enum\Enum;

/**
 * @property string $key
 */
class ActionEnum extends Enum
{
    public const VIEW = 'view';
    public const CREATE = 'create';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    protected string $primaryKey = 'key';

    protected array $fillable = [
        'key',
        'label',
    ];

    protected static function getEnumDefinition(): array
    {
        return [
            [
                'key' => self::VIEW,
                'label' => 'View Label',
            ],
            [
                'key' => self::CREATE,
                'label' => 'Create Label',
            ],
            [
                'key' => self::EDIT,
                'label' => 'Edit Label',
            ],
            [
                'key' => self::DELETE,
                'label' => 'Delete Label',
            ],
        ];
    }

    public function getNameAttribute(): string
    {
        return __("enum/action.$this->key");
    }

    public function getObjectAttribute(): object
    {
        return (object)['example'];
    }

    public function getStringAttribute(): string
    {
        return 'string';
    }

    public function getBoolAttribute(): bool
    {
        return true;
    }

    public function getIntAttribute(): int
    {
        return 10;
    }

    public function getFloatAttribute(): float
    {
        return 20.50;
    }

    public function getClassAttribute(): object
    {
        return new class {
        };
    }

    public function getStringableClassAttribute(): object
    {
        return new class {
            public function __toString(): string
            {
                return 'example';
            }
        };
    }

    public function getNullAttribute()
    {
        return null;
    }
}
