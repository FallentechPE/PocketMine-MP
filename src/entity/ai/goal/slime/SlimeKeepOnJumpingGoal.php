<?php

namespace pocketmine\entity\ai\goal\slime;

use pocketmine\entity\ai\goal\Goal;
use pocketmine\entity\monster\Slime;

class SlimeKeepOnJumpingGoal extends Goal {

	public function __construct(protected Slime $mob) {
		$this->setFlags(Goal::FLAG_JUMP, Goal::FLAG_MOVE);
	}

	public function canUse() : bool{
		return true; //TODO: check that it is not riding anything
	}

	public function tick() : void{
		$this->mob->getMoveControl()->setWantedMovement(1);
	}
}
