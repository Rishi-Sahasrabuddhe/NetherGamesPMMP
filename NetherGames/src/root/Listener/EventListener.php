<?php

declare(strict_types=1);

namespace root\Listener;

use pocketmine\player\Player;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

use \NetherGamesAssets\Cache;
use \NetherGamesAssets\NetherGamesAPI;
use \root\Player\InventoryManager;

class EventListener implements Listener
{
    private ?NetherGamesAPI $netherGamesAPI = null;
    public static ?Player $joinedPlayer = null;

    public function onEnable()
    {
        $this->netherGamesAPI = new NetherGamesAPI();
    }

    public function onJoin(PlayerJoinEvent $event): void
    {
        self::$joinedPlayer = $event->getPlayer();
        Cache::$playerStatsCached = null;
        Cache::$playerCached = false;
        Cache::cachePlayer(self::$joinedPlayer->getName());

        $playerInventory = new InventoryManager();
        $playerInventory->onEnable(self::$joinedPlayer);
    }

    
}
