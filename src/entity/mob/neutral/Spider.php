<?php

namespace pocketmine\entity\mob\neutral;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Spider extends Mob{

	public static function getNetworkTypeId() : string{
		return EntityIds::SPIDER;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(0.9, 1.4);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(16);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Spider";
	}
}