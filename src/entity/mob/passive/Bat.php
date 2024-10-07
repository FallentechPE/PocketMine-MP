<?php

namespace pocketmine\entity\mob\passive;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Bat extends Mob{

	public static function getNetworkTypeId() : string{
		return EntityIds::BAT;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(0.9, 0.5);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(6);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Bat";
	}

}