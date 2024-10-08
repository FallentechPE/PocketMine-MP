<?php

namespace pocketmine\entity\ai\goal;

use pocketmine\entity\ai\utils\DefaultPositionGenerator;
use pocketmine\entity\Entity;
use pocketmine\entity\PathfinderMob;
use pocketmine\math\Vector3;
use const M_PI_2;

class MoveTowardsTargetGoal extends Goal {

	private ?Entity $target = null;

	private Vector3 $wantedPos;

	public function __construct(
		protected PathfinderMob $mob,
		protected float $speedModifier,
		protected float $within
	) {
		$this->setFlags(Goal::FLAG_MOVE);
	}

	public function canUse() : bool{
		$target = $this->mob->getTargetEntity();
		if ($target === null) {
			return false;
		}
		$this->target = $target;

		$targetPos = $target->getPosition();
		if ($targetPos->distanceSquared($this->mob->getPosition()) > $this->within ** 2) {
			return false;
		}

		$randomPos = DefaultPositionGenerator::getPosTowards($this->mob, 16, 7, $targetPos, M_PI_2);
		if ($randomPos === null) {
			return false;
		}

		$this->wantedPos = $randomPos;

		return true;
	}

	public function canContinueToUse() : bool{
		return $this->target !== null &&
			!$this->mob->getNavigation()->isDone() &&
			$this->target->isAlive() &&
			$this->target->getPosition()->distanceSquared($this->mob->getPosition()) < $this->within ** 2;
	}

	public function start() : void{
		$this->mob->getNavigation()->moveToXYZ($this->wantedPos->x, $this->wantedPos->y, $this->wantedPos->z, $this->speedModifier);
	}

	public function stop() : void{
		$this->target = null;
	}
}
