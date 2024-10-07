<?php

namespace pocketmine\entity\mob\neutral;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class IronGolem extends Mob{

	public static function getNetworkTypeId() : string{
		return EntityIds::IRON_GOLEM;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(2.9, 1.4);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(100);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Iron Golem";
	}
}