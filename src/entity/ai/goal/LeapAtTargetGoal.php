<?php

namespace pocketmine\entity\ai\goal;

use pocketmine\entity\Entity;
use pocketmine\entity\Mob;
use pocketmine\math\Vector3;

class LeapAtTargetGoal extends Goal {

	protected Entity $target;

	public function __construct(
		protected Mob $mob,
		protected float $yMotion
	) {
		$this->setFlags(Goal::FLAG_JUMP, Goal::FLAG_MOVE);
	}

	public function canUse() : bool{
		//TODO: Check there is no passanger controlling movement

		if (!$this->mob->isOnGround()) {
			return false;
		}

		$target = $this->mob->getTargetEntity();
		if ($target === null) {
			return false;
		}

		$distanceSquared = $this->mob->getLocation()->distanceSquared($target->getLocation());
		if ($distanceSquared < 2 ** 2 || $distanceSquared > 4 ** 2) {
			return false;
		}

		if ($this->mob->getRandom()->nextBoundedInt($this->reducedTickDelay(5)) === 0) {
			$this->target = $target;
			return true;
		}

		return false;
	}

	public function canContinueToUse() : bool{
		return !$this->mob->isOnGround();
	}

	public function start() : void{
		$position = $this->mob->getLocation();
		$targetPosition = $this->target->getLocation();

		$leap = new Vector3($targetPosition->x - $position->x, 0.0, $targetPosition->z - $position->z);
		if ($leap->lengthSquared() > 0.0000001) {
			$leap = $leap->normalize()->multiply(0.4)->addVector($this->mob->getMotion()->multiply(0.2));
		}
		$leap->y = $this->yMotion;

		$this->mob->setMotion($leap);
	}

	public function stop() : void{
		unset($this->target);
	}
}
