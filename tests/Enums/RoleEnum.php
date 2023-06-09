<?php

namespace Kpebedko22\Enum\Tests\Enums;

use Kpebedko22\Enum\Enum;

/**
 * @property string $key
 */
final class RoleEnum extends Enum
{
    public const ADMIN = 'admin';
    public const MANAGER = 'manager';
    public const USUAL = 'usual';
    public const GUEST = 'guest';

    protected string $primaryKey = 'key';

    protected ?string $optionAttribute = null;

    protected array $fillable = [
        'key',
        'is_default',
        'int_number',
    ];

    protected static function getEnumDefinition(): array
    {
        return [
            [
                'key' => self::GUEST,
                'is_default' => true,
                'int_number' => 20,
            ],
            [
                'key' => self::ADMIN,
                'is_default' => false,
                'int_number' => 100,
            ],
            [
                'key' => self::MANAGER,
                'is_default' => false,
                'int_number' => 20,
            ],
            [
                'key' => self::USUAL,
                'is_default' => true,
                'int_number' => 50,
            ],
        ];
    }
}
