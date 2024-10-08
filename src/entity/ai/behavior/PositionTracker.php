<?php

namespace pocketmine\entity\ai\behavior;

use pocketmine\entity\Mob;
use pocketmine\math\Vector3;

class PositionTracker implements PosTracker {

	protected Vector3 $position;

	public function __construct(Vector3 $position) {
		$this->position = $position;
	}

	public function currentPosition() : Vector3{
		return $this->position->add(0.5, 0.5, 0.5);
	}

	public function currentBlockPosition() : Vector3{
		return clone $this->position;
	}

	public function isVisibleBy(Mob $entity) : bool{
		return true;
	}
}
