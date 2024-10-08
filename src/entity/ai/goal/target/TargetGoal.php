<?php

namespace pocketmine\entity\ai\goal\target;

use pocketmine\entity\ai\goal\Goal;
use pocketmine\entity\ai\targeting\TargetingConditions;
use pocketmine\entity\Living;
use pocketmine\entity\Mob;

abstract class TargetGoal extends Goal {

	protected ?Living $target = null;

	protected bool $canReachCache;
	protected int $reachCheckTime = 0;

	private int $unseenTicks = 0;

	protected int $unseenMemoryTicks = 60;

	public function __construct(
		protected Mob $entity,
		protected bool $mustSee,
		protected bool $mustReach = false
	) {
	}

	public function canContinueToUse() : bool{
		$target = $this->entity->getTargetEntity() ?? $this->target;
		if (!$target instanceof Living || $target->isClosed() || !$this->entity->canAttack($target)) {
			return false;
		}

		$entityPos = $this->entity->getPosition();
		$targetPos = $target->getPosition();
		if ($entityPos->world !== $targetPos->world ||
			$entityPos->distanceSquared($targetPos) > $this->getFollowDistance() ** 2
		) {
			return false;
		}

		if ($this->mustSee) {
			if ($this->entity->getSensing()->canSee($target)) {
				$this->unseenTicks = 0;
			} elseif (++$this->unseenTicks > $this->reducedTickDelay($this->unseenMemoryTicks)) {
				return false;
			}
		}

		$this->entity->setTargetEntity($target);

		return true;
	}

	public function getFollowDistance() : float{
		return $this->entity->getFollowRange();
	}

	public function start() : void{
		unset($this->canReachCache);
		$this->reachCheckTime = 0;
		$this->unseenTicks = 0;
	}

	public function stop() : void{
		$this->target = null;
		$this->entity->setTargetEntity(null);
	}

	public function canAttack(?Living $victim, TargetingConditions $conditions) : bool{
		if ($victim === null ||
			!$conditions->test($this->entity, $victim) ||
			!$this->entity->isWithinRestriction($victim->getPosition())
		) {
			return false;
		}

		if ($this->mustReach) {
			if (--$this->reachCheckTime <= 0) {
				unset($this->canReachCache);
			}

			if (!isset($this->canReachCache)) {
				$this->canReachCache = $this->canReach($victim);
			}

			if (!$this->canReachCache) {
				return false;
			}
		}

		return true;
	}

	private function canReach(Living $victim) : bool{
		$this->reachCheckTime = $this->reducedTickDelay(10 + $this->entity->getRandom()->nextBoundedInt(5));

		$path = $this->entity->getNavigation()->createPathToEntity($victim, 0);
		if ($path === null) {
			return false;
		}

		$endNode = $path->getEndNode();
		if ($endNode === null) {
			return false;
		}

		$diff = $endNode->subtractVector($victim->getPosition()->floor());
		return ($diff->x ** 2) + ($diff->z ** 2) <= 2.25;
	}

	public function setUnseenMemoryTicks(int $ticks) : self{
		$this->unseenMemoryTicks = $ticks;
		return $this;
	}
}
