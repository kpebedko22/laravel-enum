<?php

namespace Kpebedko22\Enum\Tests\Enums;

use Kpebedko22\Enum\Enum;

/**
 * @property int $id
 */
class StatusEnum extends Enum
{
    public const NEW = 1;
    public const PENDING = 2;
    public const SUCCESSFUL = 3;

    protected string $primaryKey = 'id';

    protected array $fillable = [
        'id',
    ];

    protected static function getEnumDefinition(): array
    {
        return [
            [
                'id' => self::NEW,
            ],
            [
                'id' => self::PENDING,
            ],
            [
                'id' => self::SUCCESSFUL,
            ],
        ];
    }
}
