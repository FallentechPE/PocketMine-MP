<?php

namespace pocketmine\entity\mob\hostile;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Endermite extends Mob{

	public static function getNetworkTypeId() : string{
		return EntityIds::ENDERMITE;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(0.3, 0.4);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(8);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Endermite";
	}
}