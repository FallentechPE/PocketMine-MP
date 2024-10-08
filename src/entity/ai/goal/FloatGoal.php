<?php

namespace pocketmine\entity\ai\goal;

use pocketmine\block\VanillaBlocks;
use pocketmine\entity\Mob;

class FloatGoal extends Goal {

	public function __construct(protected Mob $mob) {
		$this->setFlags(Goal::FLAG_JUMP);
		$mob->getNavigation()->setCanFloat();
	}

	public function canUse() : bool{
		return $this->mob->getImmersionPercentage(VanillaBlocks::WATER()) > $this->mob->getFluidJumpThreshold() || $this->mob->isInLava();
	}

	public function requiresUpdateEveryTick() : bool{
		return true;
	}

	public function tick() : void{
		if ($this->mob->getRandom()->nextFloat() < 0.8) {
			$this->mob->getJumpControl()->jump();
		}
	}
}
