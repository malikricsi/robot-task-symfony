<?php

namespace App\Enum;

class RobotEnum
{
    public const TYPE_BRAWLER = 'brawler';
    public const TYPE_ROUGE = 'rouge';
    public const TYPE_ASSAULT = 'assault';

    public const TYPES = [
        self::TYPE_BRAWLER,
        self::TYPE_ROUGE,
        self::TYPE_ASSAULT,
    ];

    public static function getTypes(): array
    {
        return static::TYPES;
    }
}