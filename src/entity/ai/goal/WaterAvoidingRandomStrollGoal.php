<?php

namespace pocketmine\entity\ai\goal;

use pocketmine\block\Water;
use pocketmine\entity\ai\utils\LandPositionGenerator;
use pocketmine\entity\PathfinderMob;
use pocketmine\math\Vector3;

class WaterAvoidingRandomStrollGoal extends RandomStrollGoal {

	public const DEFAULT_PROBABILITY = 0.001;

	public function __construct(
		PathfinderMob $entity, float $speedModifier,
		protected float $probability = self::DEFAULT_PROBABILITY
	) {
		parent::__construct($entity, $speedModifier);
	}

	public function getPosition() : ?Vector3{
		if ($this->entity->getWorld()->getBlock($this->entity->getPosition()) instanceof Water) {
			return LandPositionGenerator::getPos($this->entity, 15, 7) ?? parent::getPosition();
		}

		if ($this->entity->getRandom()->nextFloat() >= $this->probability) {
			return LandPositionGenerator::getPos($this->entity, 10, 7);
		}

		return parent::getPosition();
	}
}
