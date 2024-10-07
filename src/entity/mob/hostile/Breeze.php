<?php

namespace pocketmine\entity\mob\hostile;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Breeze extends Mob{

	public static function getNetworkTypeId() : string{
		return EntityIds::BREEZE;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(1.77, 0.6);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(30);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Breeze";
	}
}