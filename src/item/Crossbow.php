<?php

namespace pocketmine\item;

use pocketmine\player\Player;

class Crossbow extends Tool implements Releasable{

	public function getMaxDurability() : int{
		return 464;
	}

	public function canStartUsingItem(Player $player) : bool{
		return !$player->hasFiniteResources() || $player->getOffHandInventory()->contains($arrow = VanillaItems::ARROW()) || $player->getInventory()->contains($arrow);
	}
}