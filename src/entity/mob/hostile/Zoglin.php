<?php

namespace pocketmine\entity\mob\hostile;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Zoglin extends Mob{

	public static function getNetworkTypeId() : string{
		return EntityIds::ZOGLIN;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(1.4, 1.3965);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(40);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Zoglin";
	}
}