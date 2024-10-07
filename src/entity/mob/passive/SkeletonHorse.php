<?php

namespace pocketmine\entity\mob\passive;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class SkeletonHorse extends Mob{

	public static function getNetworkTypeId() : string{
		return EntityIds::SKELETON_HORSE;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(1.6, 1.4);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(15);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Skeleton Horse";
	}
}