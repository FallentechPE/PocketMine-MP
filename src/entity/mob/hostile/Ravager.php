<?php

namespace pocketmine\entity\mob\hostile;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Ravager extends Mob{

	public static function getNetworkTypeId() : string{
		return EntityIds::RAVAGER;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(2.2, 1.95);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(100);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Ravager";
	}
}