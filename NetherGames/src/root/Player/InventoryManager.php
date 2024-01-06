<?php

declare(strict_types=1);

namespace root\Player;

use pocketmine\player\Player;
use pocketmine\item\VanillaItems;

use root\Listener\EventListener;


class InventoryManager
{
	public function onEnable(Player $player)
	{
		$this->clearInventory($player);
	}

	public function clearInventory(Player $player)
	{
		$playerInventory = $player->getInventory();
		for ($i = 0; $i <= 35; $i++) {
			$playerInventory->setItem($i, VanillaItems::AIR());
		}
	}

	public function setItem(Player $player, int $slot, $item)
	{
		if (0 <= $slot && $slot <= 36) {
			$player->getInventory()->setItem($slot, $item);
		} else {
			return false;
		}
	}
}
