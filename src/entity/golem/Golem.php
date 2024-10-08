<?php

namespace pocketmine\entity\golem;

use pocketmine\entity\PathfinderMob;

abstract class Golem extends PathfinderMob {

	public function getAmbientSoundInterval() : float{
		return 8;
	}

	public function getAmbientSoundIntervalRange() : float{
		return 16;
	}

	public function shouldDespawnWhenFarAway(float $distanceSquared) : bool{
		return false;
	}
}
