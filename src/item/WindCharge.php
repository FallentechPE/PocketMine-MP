<?php

namespace pocketmine\item;

use pocketmine\entity\Location;
use pocketmine\entity\projectile\Throwable;
use pocketmine\entity\projectile\WindCharge as WindChargeEntity;
use pocketmine\player\Player;

class WindCharge extends ProjectileItem{

	protected function createEntity(Location $location, Player $thrower) : Throwable{
		return new WindChargeEntity($location, $thrower);
	}

	public function getThrowForce() : float{
		return 1.5;
	}

	public function getCooldownTicks() : int{
		return 10;
	}
}