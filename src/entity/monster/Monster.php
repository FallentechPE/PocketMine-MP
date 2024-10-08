<?php

namespace pocketmine\entity\monster;

use pocketmine\entity\MobCategory;
use pocketmine\entity\PathfinderMob;

abstract class Monster extends PathfinderMob implements Enemy {
	//TODO!

	public function getMobCategory() : MobCategory{
		return MobCategory::MONSTER();
	}

	public function shouldDespawnInPeaceful() : bool{
		return true;
	}

	public function getXpDropAmount() : int{
		if ($this->hasBeenDamagedByPlayer()) {
			return 5;
		}

		return 0;
	}
}
