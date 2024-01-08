<?php

declare(strict_types=1);

namespace root;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

use root\Listener\EventListener;
use root\commands\stats\StatsForms;
use root\Worlds\WorldsManager;
use root\Player\NametagManager;
use NetherGamesAssets\Cache;
use Minigames\Spleef\SpleefConfig;
use NetherGamesAssets\NetherGamesAPI;

class Main extends PluginBase
{

    private ?NetherGamesAPI $netherGamesAPI = null;
    private ?NametagManager $nametagManager = null;
    // private ?WorldsManager $worldsManager = null;
    public ?Cache $cache = null;
    private ?StatsForms $forms = null;

    private static $registered = false;


    public function onEnable(): void
    {
        $this->netherGamesAPI = new NetherGamesAPI();
        // $this->worldsManager = new WorldsManager();
        $this->nametagManager = new NametagManager();
        $this->cache = new Cache();
        $this->forms = new StatsForms();

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        $player = Server::getInstance()->getPlayerExact($sender->getName());
        switch ($command->getName()) {
            case "stats":
                if (!$sender instanceof Player) {
                    $sender->sendMessage("Error: You may only run this command as a player.");
                    return false;
                }
                if (isset($args[0])) {
                    if ($this->netherGamesAPI->getPlayerName($args[0]) !== 0) {
                        $playerName = str_replace('"', '', $this->netherGamesAPI->getPlayerName($args[0]));
                        if (strcasecmp($playerName, $args[0]) === 0) {
                            $this->forms->statsSelectionForm($sender, $playerName);
                        } else {
                            return false;
                        }
                    }
                    $sender->sendMessage("Error: Player '" . $args[0] . "' does not exist on the NetherGames database.");
                    return false;
                } else {
                    if ($this->netherGamesAPI->getPlayerName($sender->getName()) !== null) {
                        $this->forms->statsSelectionForm($sender);
                    } else {
                        $sender->sendMessage("Error: Player '" . $sender->getName() . "' does not exist on the NetherGames database.");
                    }
                }
                break;
            case 'play':
                if (!$sender instanceof Player) {
                    $sender->sendMessage("Error: You may only run this command as a player.");
                    return false;
                }
                if (!isset($args[0])) {
                    $sender->sendMessage("Error: Please include a game");
                    break;
                }
                switch (strtolower($args[0])) {
                    case 'spleef':
                        if (self::$registered === false) {
                            $this->getServer()->getPluginManager()->registerEvents(new SpleefConfig($this->getServer()), $this);
                            self::$registered = true;
                        }
                        SpleefConfig::configSpleef(EventListener::$joinedPlayer);
                        break;

                    default:
                        $sender->sendMessage("Error: Unknown game: $args[0].");
                        break;
                }
                break;
            case 'lobby':
                $sender->sendMessage("Lobby not configured");
                break;
            default:
                throw new \AssertionError("This line will never be executed");
        }
        return true;
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        Cache::$playerStatsCached = null;
        Cache::$playerCached = false;
        $player = $event->getPlayer();
        $this->cache->cachePlayer($player->getName());
    }
}
