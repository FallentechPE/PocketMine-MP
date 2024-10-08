<?php

namespace pocketmine\entity\mob;

use pocketmine\entity\Living;
use pocketmine\item\ItemTypeIds;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;

abstract class Mob extends Living{

	private bool $isBaby = false;

	public function onInteract(Player $player, Vector3 $clickPos) : bool{
		$heldItem = $player->getInventory()->getItemInHand();
		if($heldItem->getTypeId() === ItemTypeIds::NAME_TAG){
			if($heldItem->hasCustomName()){
				$this->setNameTag($heldItem->getCustomName());
			}
		}
		return false;
	}

	public function saveNBT() : CompoundTag{
		$nbt = parent::saveNBT();
		$nbt->setByte("IsBaby", $this->isBaby ? 1 : 0);
		return $nbt;
	}

	public function isBaby() : bool{
		return $this->isBaby;
	}

	public function setIsBaby(bool $isBaby) : self{
		if($this->isBaby !== $isBaby){
			$this->setScale($isBaby ? 0.5 : 1);
		}
		$this->isBaby = $isBaby;
		return $this;
	}

}