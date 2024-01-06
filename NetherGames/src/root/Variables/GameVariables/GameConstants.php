<?php

declare(strict_types=1);

namespace root\Variables\GameVariables;

use pocketmine\item\VanillaItems;

class GameConstants
{
    public static function clearBlockDrops(): array
    {
        return [VanillaItems::AIR()];
    }
}
