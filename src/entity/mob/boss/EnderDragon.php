<?php

namespace pocketmine\entity\mob\boss;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class EnderDragon extends Mob{

	public static function getNetworkTypeId() : string{
		return EntityIds::ENDER_DRAGON;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(8, 16);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(200);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Ender Dragon";
	}
}