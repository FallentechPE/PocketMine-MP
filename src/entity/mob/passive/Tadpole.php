<?php

namespace pocketmine\entity\mob\passive;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\entity\WaterAnimal;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Tadpole extends WaterAnimal{

	public static function getNetworkTypeId() : string{
		return EntityIds::TADPOLE;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(0.6, 0.8);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(6);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Tadpole";
	}
}