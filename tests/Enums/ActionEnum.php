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
        'key'
    ];

    protected static function getEnumDefinition(): array
    {
        return [
            [
                'key' => self::VIEW,
                'is_available_to_everyone' => true,
            ],
            [
                'key' => self::CREATE,
                'is_available_to_everyone' => false,
            ],
            [
                'key' => self::EDIT,
                'is_available_to_everyone' => false,
            ],
            [
                'key' => self::DELETE,
                'is_available_to_everyone' => false,
            ],
        ];
    }
}
