<?php

namespace pocketmine\entity\mob\hostile;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class MagmaCube extends Mob{

	public static function getNetworkTypeId() : string{
		return EntityIds::MAGMA_CUBE;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(2.08, 2.08);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(16);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Magma Cube";
	}
}