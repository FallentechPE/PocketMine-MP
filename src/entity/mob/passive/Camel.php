<?php

namespace pocketmine\entity\mob\passive;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Camel extends Mob{

	public static function getNetworkTypeId() : string{
		return EntityIds::CAMEL;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(2.375, 1.7);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(32);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Camel";
	}
}