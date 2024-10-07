<?php

namespace pocketmine\entity\mob\hostile;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Slime extends Mob{

	public static function getNetworkTypeId() : string{
		return EntityIds::SLIME;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(1.04, 1.04);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(4);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Slime";
	}
}