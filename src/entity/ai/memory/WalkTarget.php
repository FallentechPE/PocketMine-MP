<?php

namespace pocketmine\entity\ai\memory;

use pocketmine\entity\ai\behavior\PosTracker;

class WalkTarget {

	private PosTracker $target;

	private float $speedModifier;

	private int $closeEnoughDist;

	public function __construct(PosTracker $target, float $speedModifier, int $closeEnoughDist) {
		$this->target = $target;
		$this->speedModifier = $speedModifier;
		$this->closeEnoughDist = $closeEnoughDist;
	}

	public function getTarget() : PosTracker {
		return $this->target;
	}

	public function getSpeedModifier() : float {
		return $this->speedModifier;
	}

	public function getCloseEnoughDist() : int {
		return $this->closeEnoughDist;
	}
}
