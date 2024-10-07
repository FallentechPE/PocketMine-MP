<?php

namespace pocketmine\entity\mob;

use pocketmine\entity\Living;
use pocketmine\item\ItemTypeIds;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

abstract class Mob extends Living{

	public function onInteract(Player $player, Vector3 $clickPos) : bool{
		$heldItem = $player->getInventory()->getItemInHand();
		if ($heldItem->getTypeId() === ItemTypeIds::NAME_TAG) {
			if ($heldItem->hasCustomName()) {
				$this->setNameTag($heldItem->getCustomName());
			}
		}
		return false;
	}

}