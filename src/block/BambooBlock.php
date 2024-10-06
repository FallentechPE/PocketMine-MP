<?php

namespace pocketmine\block;

use pocketmine\block\utils\PillarRotationTrait;

class BambooBlock extends Opaque{
	use PillarRotationTrait;

	public function getFlameEncouragement() : int{
		return 20;
	}

	public function getFlammability() : int{
		return 5;
	}

}