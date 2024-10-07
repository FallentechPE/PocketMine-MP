<?php

namespace pocketmine\entity\mob\neutral;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Mob;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class TraderLlama extends Mob{

	public static function getNetworkTypeId() : string{
		return EntityIds::TRADER_LLAMA;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(1.87, 0.9);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(15);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Trader Llama";
	}
}