<?php

namespace pocketmine\entity\ai\goal;

use pocketmine\entity\ai\utils\DefaultPositionGenerator;
use pocketmine\entity\PathfinderMob;
use pocketmine\math\Vector3;

class RandomStrollGoal extends Goal {

	public const DEFAULT_INTERVAL = 120;

	protected Vector3 $wantedPosition;

	protected bool $forceTrigger = false;

	public function __construct(
		protected PathfinderMob $entity,
		protected float $speedModifier,
		protected int $interval = self::DEFAULT_INTERVAL,
		protected bool $checkNoActionTime = true
	) {
		$this->setFlags(Goal::FLAG_MOVE);
	}

	public function canUse() : bool{
		// TODO: is Vehicle check

		if (!$this->forceTrigger && (
				($this->checkNoActionTime && $this->entity->getNoActionTime() >= 100) ||
				$this->entity->getRandom()->nextBoundedInt($this->reducedTickDelay($this->interval)) !== 0
			)) {
			return false;
		}

		$position = $this->getPosition();
		if ($position === null) {
			return false;
		}

		$this->wantedPosition = $position;
		$this->forceTrigger = false;

		return true;
	}

	public function getPosition() : ?Vector3{
		return DefaultPositionGenerator::getPos($this->entity, 10, 7);
	}

	public function canContinueToUse() : bool{
		return !$this->entity->getNavigation()->isDone();
	}

	public function start() : void{
		$this->entity->getNavigation()->moveToXYZ($this->wantedPosition->x, $this->wantedPosition->y, $this->wantedPosition->z, $this->speedModifier);
	}

	public function stop() : void{
		$this->entity->getNavigation()->stop();
		parent::stop();
	}

	public function trigger() : void{
		$this->forceTrigger = true;
	}

	public function getInterval() : int{
		return $this->interval;
	}

	/**
	 * @return $this
	 */
	public function setInterval(int $interval) : self{
		$this->interval = $interval;

		return $this;
	}
}
