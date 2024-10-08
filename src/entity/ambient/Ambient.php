<?php

namespace pocketmine\entity\ambient;

use pocketmine\entity\Mob;
use pocketmine\entity\MobCategory;

abstract class Ambient extends Mob {

	public function getMobCategory() : MobCategory{
		return MobCategory::AMBIENT();
	}
}
