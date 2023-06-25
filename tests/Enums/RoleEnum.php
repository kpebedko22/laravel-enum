<?php

namespace Kpebedko22\LaravelEnum\Tests\Enums;

use Kpebedko22\LaravelEnum\Enum;

/**
 * @property string $key
 */
final class RoleEnum extends Enum
{
    public const ADMIN = 'admin';
    public const MANAGER = 'manager';
    public const USUAL = 'usual';

    protected string $primaryKey = 'key';

    protected array $fillable = [
        'key',
        'is_default',
    ];

    protected static function getEnumDefinition(): array
    {
        return [
            [
                'key' => self::ADMIN,
                'is_default' => false,
                'int_number' => 100,
            ],
            [
                'key' => self::MANAGER,
                'is_default' => false,
                'int_number' => 62,
            ],
            [
                'key' => self::USUAL,
                'is_default' => true,
                'int_number' => 14,
            ],
        ];
    }
}