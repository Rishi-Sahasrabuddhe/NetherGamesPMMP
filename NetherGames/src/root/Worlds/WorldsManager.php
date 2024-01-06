<?php

declare(strict_types=1);

namespace root\Worlds;

use pocketmine\Server;
use pocketmine\world\generator\normal\Normal;
use pocketmine\world\generator\Flat;
use pocketmine\world\World;
use pocketmine\world\WorldManager;
use pocketmine\world\WorldCreationOptions;

use root\Listener\EventListener;
use root\Variables\GameVariables\GameVariables;



class WorldsManager
{
    private $server;
    private $worldManager;
    private $worldCreationOptions;

    // private ?GameVariables $gameVar = null;

    // public function __construct()
    // {
    //     $this->server = Server::getInstance();
    //     $this->worldManager = $this->server->getWorldManager();
    //     $this->worldCreationOptions = new WorldCreationOptions();
    // }

    // public function getWorld(string $worldName)
    // {
    //     return $this->worldManager->getWorldByName($worldName);
    // }

    // public function joinWorld(string $worldName)
    // {
    //     $this->loadWorld($worldName);
    //     return $this->worldManager->getWorldByName($worldName);
    // }

    // public function loadWorld(string $worldName)
    // {
    //     return $this->worldManager->loadWorld($worldName);
    // }


    // public function getWorldInstance(string $worldName, int $worldID): string
    // {
    //     // $this->worldManager->generateWorld()
    // }
}
