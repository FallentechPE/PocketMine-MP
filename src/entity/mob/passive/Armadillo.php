<?php

namespace pocketmine\entity\mob\passive;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Armadillo extends Mob{

	public static function getNetworkTypeId() : string{
		return EntityIds::ARMADILLO;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(0.65, 0.7);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(12);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Allay";
	}
}