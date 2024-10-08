<?php

namespace pocketmine\entity\ai\goal\slime;

use pocketmine\entity\ai\goal\Goal;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\monster\Slime;

class SlimeRandomDirectionGoal extends Goal {

	protected float $chosenDegrees;

	protected int $nextRandomizeTime = 0;

	public function __construct(protected Slime $mob) {
		$this->setFlags(Goal::FLAG_LOOK);
	}

	public function canUse() : bool{
		return $this->mob->getTargetEntityId() === null && (
				$this->mob->isOnGround() ||
				$this->mob->isInWater() ||
				$this->mob->isInLava() ||
				$this->mob->getEffects()->has(VanillaEffects::LEVITATION())
			);
	}

	public function tick() : void{
		if (--$this->nextRandomizeTime <= 0) {
			$this->nextRandomizeTime = $this->adjustedTickDelay(40 + $this->mob->getRandom()->nextBoundedInt(60));
			$this->chosenDegrees = $this->mob->getRandom()->nextBoundedInt(360);
		}

		$this->mob->getMoveControl()->setDirection($this->chosenDegrees, false);
	}
}
