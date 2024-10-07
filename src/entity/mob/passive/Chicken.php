<?php

namespace pocketmine\entity\mob\passive;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Chicken extends Mob{

	public static function getNetworkTypeId() : string{
		return EntityIds::CHICKEN;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(0.8, 0.6);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(4);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Chicken";
	}
}