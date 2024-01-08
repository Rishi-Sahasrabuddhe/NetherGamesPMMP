<?php

declare(strict_types=1);

namespace root\Listener;


use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Server;

use \NetherGamesAssets\Cache;
use \NetherGamesAssets\NetherGamesAPI;
use \root\Player\InventoryManager;
use \root\Player\NametagManager;

class EventListener implements Listener
{
    private ?NetherGamesAPI $netherGamesAPI = null;
    private ?NametagManager $nametagManager = null;
    public static ?Player $joinedPlayer = null;

    public function onEnable(): void
    {
        $this->netherGamesAPI = new NetherGamesAPI();
    }

    public function onJoin(PlayerJoinEvent $event): void
    {
        $this->nametagManager = new NametagManager();
        // self::$joinedPlayer = Server::getPlayer($event->getPlayer()->getName());
        self::$joinedPlayer = $event->getPlayer();
        Cache::$playerStatsCached = null;
        Cache::$playerCached = false;
        Cache::cachePlayer(self::$joinedPlayer->getName());

        $playerInventory = new InventoryManager();
        $playerInventory->onEnable(self::$joinedPlayer);
        $this->nametagManager->setDefaultNametag(self::$joinedPlayer);
    }
}
