<?php

namespace pocketmine\entity\mob\neutral;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Piglin extends Mob{

	public static function getNetworkTypeId() : string{
		return EntityIds::PIGLIN;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(1.95, 0.6);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(7);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Piglin";
	}
}