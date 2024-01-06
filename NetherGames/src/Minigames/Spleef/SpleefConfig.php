<?php

declare(strict_types=1);

namespace Minigames\Spleef;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\VanillaItems;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\ProjectileHitBlockEvent;

use \root\Player\InventoryManager;
use \root\Variables\GameVariables\GameConstants;

class SpleefConfig implements Listener
{
    private static int $score;
    private Server $server;

    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    public function onEnable()
    {
    }

    public static function configSpleef(Player $player)
    {
        self::$score = 0;
        $playerInventory = new InventoryManager();
        $playerInventory->clearInventory($player);
        $playerInventory->setItem($player, 0, VanillaItems::WOODEN_SHOVEL());
    }

    public function snowballHitsSnow(ProjectileHitBlockEvent $event)
    {
        $entity = $event->getEntity();
        $block = $event->getBlockHit();
        if (str_contains(strtolower(strval($entity)), "snowball") && $block->getTypeId() === 10472) {
            $this->server->getWorldManager()->getWorldByName("world")->setBlock($block->getPosition(), VanillaBlocks::AIR());
            self::$score += 2;
        }
    }

    public function onBlockBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $item = $event->getItem();

        switch ($block->getName()) {
            case "Snow Block":
                self::$score++;
                $player->sendMessage("Score: " . self::$score);
                $event->setDrops(GameConstants::clearBlockDrops());
                $player->getInventory()->addItem(VanillaItems::SNOWBALL());
                break;
        }
    }
}
