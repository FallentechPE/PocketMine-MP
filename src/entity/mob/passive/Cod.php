<?php

namespace pocketmine\entity\mob\passive;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\entity\WaterAnimal;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Cod extends WaterAnimal{

	public static function getNetworkTypeId() : string{
		return EntityIds::COD;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(0.3, 0.6);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(3);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Cod";
	}
}