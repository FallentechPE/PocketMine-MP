<?php

namespace pocketmine\entity\ai\navigation;

use pocketmine\entity\Entity;
use pocketmine\entity\pathfinder\Path;
use pocketmine\math\Vector3;

class WallClimberNavigation extends GroundPathNavigation{

	private ?Vector3 $pathToPosition = null;

	public function createPathToPosition(Vector3 $position, int $reach, ?float $maxDistanceFromStart = null) : ?Path{
		$this->pathToPosition = $position->floor();
		$this->speedModifier = 1;

		return parent::createPathToPosition($position, $reach, $maxDistanceFromStart);
	}

	public function moveToEntity(Entity $target, float $speedModifier) : bool{
		$path = $this->createPathToEntity($target, 0);
		if ($path !== null) {
			return $this->moveToPath($path, $speedModifier);
		}

		$this->pathToPosition = $target->getPosition()->floor();
		$this->speedModifier = $speedModifier;

		return true;
	}

	public function tick() : void{
		if (!$this->isDone()) {
			parent::tick();
			return;
		}

		if ($this->pathToPosition === null) {
			return;
		}

		$mobPosition = $this->mob->getLocation();
		$mobWidthSqr = $this->mob->getSize()->getWidth() ** 2;
		$targetPosition = $this->pathToPosition->add(0.5, 0.5, 0.5);
		if (!($targetPosition->distanceSquared($mobPosition) < $mobWidthSqr) && (
				!($mobPosition->y > $this->pathToPosition->y) ||
				!($targetPosition->withComponents(null, $mobPosition->y, null)->distanceSquared($mobPosition) < $mobWidthSqr)
			)
		) {
			$this->mob->getMoveControl()->setWantedPosition($this->pathToPosition, $this->speedModifier);
		} else {
			$this->pathToPosition = null;
		}
	}
}
