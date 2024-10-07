<?php

namespace pocketmine\entity\mob\passive;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Rabbit extends Mob{

	public static function getNetworkTypeId() : string{
		return EntityIds::RABBIT;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(0.402, 0.402);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(3);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Rabbit";
	}
}