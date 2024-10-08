<?php

namespace pocketmine\entity\ai\goal;

use pocketmine\entity\Mob;
use function cos;
use function sin;
use const M_PI;

class RandomLookAroundGoal extends Goal {

	protected float $relX;

	protected float $relZ;

	protected int $lookTime;

	public function __construct(protected Mob $entity) {
		$this->setFlags(Goal::FLAG_MOVE, Goal::FLAG_LOOK);
	}

	public function canUse() : bool{
		return $this->entity->getRandom()->nextFloat() < 0.02;
	}

	public function canContinueToUse() : bool{
		return $this->lookTime >= 0;
	}

	public function start() : void{
		$randomRadians = M_PI * 2 * $this->entity->getRandom()->nextFloat();

		$this->relX = cos($randomRadians);
		$this->relZ = sin($randomRadians);

		$this->lookTime = 20 + $this->entity->getRandom()->nextBoundedInt(20);
	}

	public function requiresUpdateEveryTick() : bool{
		return true;
	}

	public function tick() : void{
		$this->lookTime--;

		$this->entity->getLookControl()->setLookAt($this->entity->getEyePos()->add($this->relX, 0, $this->relZ));
	}
}
