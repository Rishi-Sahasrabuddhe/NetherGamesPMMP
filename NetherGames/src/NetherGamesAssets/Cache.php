<?php

declare(strict_types=1);

namespace NetherGamesAssets;

class Cache
{
    private ?NetherGamesAPI $netherGamesAPI = null;

    public function __construct()
    {
        $this->netherGamesAPI = new \NetherGamesAssets\NetherGamesAPI();
    }

    public static $playerCached = false;
    public static $playerStatsCached = null;

    public static function cachePlayer($target)
    {
        $cacheInstance = new self();
        $cacheInstance->netherGamesAPI->getPlayerStats($target);

        self::$playerStatsCached = $cacheInstance->netherGamesAPI->getPlayerStats($target);

        if (self::$playerStatsCached !== null) {
            self::$playerCached = true;
        } else {
            return false;
        }
    }
}
