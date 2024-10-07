<?php

namespace pocketmine\entity\mob\passive;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Mooshroom extends Mob{

	public static function getNetworkTypeId() : string{
		return EntityIds::MOOSHROOM;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(1.3, 0.9);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(10);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Mooshroom";
	}
}