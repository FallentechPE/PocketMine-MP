<?php

namespace pocketmine\entity\mob\passive;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\entity\WaterAnimal;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Axolotl extends WaterAnimal{

	public static function getNetworkTypeId() : string{
		return EntityIds::AXOLOTL;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(0.42, 0.75);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(14);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Axolotl";
	}
}