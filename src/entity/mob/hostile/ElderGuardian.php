<?php

namespace pocketmine\entity\mob\hostile;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class ElderGuardian extends Mob{

	public static function getNetworkTypeId() : string{
		return EntityIds::ELDER_GUARDIAN;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(1.9975, 1.9975);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(80);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Elder Guardian";
	}
}