<?php

namespace pocketmine\entity\mob\passive;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Parrot extends Mob{

	public static function getNetworkTypeId() : string{
		return EntityIds::PARROT;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(1.0, 0.5);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(6);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Parrot";
	}
}