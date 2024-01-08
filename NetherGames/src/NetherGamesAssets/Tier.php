<?php

declare(strict_types=1);

namespace NetherGamesAssets;

use Exception;



class Tier
{
    const STEEL = 'STEEL';
    const BRONZE = 'BRONZE';
    const SILVER = 'SILVER';
    const GOLD = 'GOLD';
    const OPAL = 'OPAL';
    const AMETHYST = 'AMETHYST';
    const SAPPHIRE = 'SAPPHIRE';
    const DIAMOND = 'DIAMOND';

    public static function getFormattedTagColour(string $tier): string
    {
        $tier = strtoupper($tier);
        switch ($tier) {
            case self::STEEL:
                return '§8';
            case self::BRONZE:
                return '§e';
            case self::SILVER:
                return '§7';
            case self::GOLD:
                return '§6';
            case self::OPAL:
                return '§9';
            case self::AMETHYST:
                return '§d';
            case self::SAPPHIRE:
                return '§1';
            case self::DIAMOND:
                return '§c';
            default:
                throw new Exception("Tier not found");
        }
    }

    public static function getName(string $tier): string
    {
        $tier = strtoupper($tier);
        switch ($tier) {
            case self::STEEL:
                return "Steel";
            case self::BRONZE:
                return "Bronze";
            case self::SILVER:
                return "Silver";
            case self::GOLD:
                return "Gold";
            case self::OPAL:
                return "Opal";
            case self::AMETHYST:
                return "Amethyst";
            case self::SAPPHIRE:
                return "Sapphire";
            case self::DIAMOND:
                return "Diamond";
            default:
                throw new Exception("Unknown Tier");
        }
    }
}
