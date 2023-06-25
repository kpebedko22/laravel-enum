<?php

namespace Kpebedko22\LaravelEnum\Tests\Enums;

class StatusEnum extends \Kpebedko22\LaravelEnum\Enum
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
