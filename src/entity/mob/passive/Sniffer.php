<?php

namespace pocketmine\entity\mob\passive;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Sniffer extends Mob{

	public static function getNetworkTypeId() : string{
		return EntityIds::SNIFFER;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(1.75, 1.9);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(14);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Sniffer";
	}
}