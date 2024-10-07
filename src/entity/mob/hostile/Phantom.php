<?php

namespace pocketmine\entity\mob\hostile;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Phantom extends Mob{

	public static function getNetworkTypeId() : string{
		return EntityIds::PHANTOM;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(0.5, 0.9);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(20);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Phantom";
	}
}