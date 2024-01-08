<?php

declare(strict_types=1);

namespace root\Player;

use pocketmine\player\Player;

use root\Listener\EventListener;
use NetherGamesAssets\Cache;
use NetherGamesAssets\NetherGamesAPI;
use NetherGamesAssets\Tier;


class NametagManager
{
    private ?Cache $cache = null;
    public function onEnable()
    {
    }

    public function setDefaultNametag(Player $player)
    {
        $player->setDisplayName($this->getNametag($player));
    }

    protected function getNametag(Player $player)
    {
        $this->cache = new Cache();
        $this->cache->cachePlayer($player->getName());
        $level = Cache::$playerStatsCached['formattedLevel'] . " ";
        if (Cache::$playerStatsCached['tier'] !== null) {
            $tier = Cache::$playerStatsCached['tier'];
            $clr = Tier::getFormattedTagColour($tier);
            $name = Tier::getName($tier);
            $tier = "$clr$name ";
        } else {
            $tier = "";
        }
        if (count(Cache::$playerStatsCached['ranks']) > 0) {
            $rank = Cache::$playerStatsCached['ranks'][0] . " ";
            $rank = strtoupper($rank);
        } else {
            $rank = "";
        }
        $name = Cache::$playerStatsCached['name'];
        Cache::cacheGuild(true, $player->getName());
        if (isset(Cache::$guildStatsCached['rawTag']) && Cache::$guildStatsCached['rawTag'] !== null) {
            $guild = Cache::$guildStatsCached['rawTag'] . " ";
        } else {
            $guild = "";
        }

        return " §l" . $level . $tier . "§r§l$rank" . "§r$name §l" . $guild . "§r";
    }
}
