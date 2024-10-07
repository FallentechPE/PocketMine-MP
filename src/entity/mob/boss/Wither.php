<?php

namespace pocketmine\entity\mob\boss;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Wither extends Mob{

	public static function getNetworkTypeId() : string{
		return EntityIds::WITHER;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(3.0, 1.0);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(450);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Wither";
	}
}