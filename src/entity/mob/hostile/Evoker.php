<?php

namespace pocketmine\entity\mob\hostile;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Evoker extends Mob{

	public static function getNetworkTypeId() : string{
		return EntityIds::EVOCATION_ILLAGER;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(1.9, 0.6);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(24);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Evoker";
	}
}