<?php

namespace pocketmine\entity\ai\goal\slime;

use pocketmine\entity\ai\goal\Goal;
use pocketmine\entity\monster\Slime;

class SlimeFloatGoal extends Goal {

	public function __construct(protected Slime $mob) {
		$this->setFlags(Goal::FLAG_JUMP, Goal::FLAG_MOVE);
		$mob->getNavigation()->setCanFloat();
	}

	public function canUse() : bool{
		return $this->mob->isInWater() || $this->mob->isInLava();
	}

	public function requiresUpdateEveryTick() : bool{
		return true;
	}

	public function tick() : void{
		if ($this->mob->getRandom()->nextFloat() < 0.8) {
			$this->mob->getJumpControl()->jump();
		}

		$this->mob->getMoveControl()->setWantedMovement(1.2);
	}
}
