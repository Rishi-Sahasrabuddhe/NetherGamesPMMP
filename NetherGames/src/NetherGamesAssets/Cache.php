<?php

declare(strict_types=1);

namespace NetherGamesAssets;

use root\Listener\EventListener;

class Cache
{
    private ?NetherGamesAPI $netherGamesAPI = null;

    public function __construct()
    {
        $this->netherGamesAPI = new \NetherGamesAssets\NetherGamesAPI();
    }

    public static $playerCached = false;
    public static $guildCached = false;
    public static $factionCached = false;
    public static $playerStatsCached = null;
    public static $guildStatsCached = null;
    public static $factionStatsCached = null;

    public static function cachePlayer($target)
    {
        $cacheInstance = new self();
        // $cacheInstance->netherGamesAPI->getPlayerStats($target);

        self::$playerStatsCached = $cacheInstance->netherGamesAPI->getPlayerStats($target);

        if (self::$playerStatsCached !== null) {
            self::$playerCached = true;
        } else {
            return false;
        }
    }

    public static function cacheGuild(bool $fromPlayer = false, string $target = "", string $guild = "")
    {
        $cacheInstance = new self();

        self::$guildStatsCached = $cacheInstance->netherGamesAPI->getGuildStats($fromPlayer, $target, $guild);

        if (self::$guildStatsCached !== null) {
            self::$guildCached = true;
        } else {
            return false;
        }
    }

    public static function cacheFaction(bool $fromPlayer = false, string $target = "", string $faction = "")
    {
        $cacheInstance = new self();

        self::$guildStatsCached = $cacheInstance->netherGamesAPI->getFactionStats($fromPlayer, $target, $faction);

        if (self::$guildStatsCached !== null) {
            self::$guildCached = true;
        } else {
            return false;
        }
    }
}
