<?php

namespace pocketmine\entity\ai\goal;

use pocketmine\entity\Entity;
use pocketmine\entity\Mob;
use pocketmine\utils\Utils;
use function floor;
use function sqrt;

class RangedAttackGoal extends Goal {

	protected Entity $target;

	protected float $attackRadiusSquared;

	protected int $seeTime = 0;
	protected int $attackTime = -1;

	public function __construct(
		protected Mob $mob,
		protected float $speedModifier,
		protected int $minAttackInterval,
		protected int $maxAttackInterval,
		protected float $attackRadius
	) {
		$this->attackRadiusSquared = $attackRadius ** 2;

		$this->setFlags(Goal::FLAG_MOVE, Goal::FLAG_LOOK);
	}

	public function canUse() : bool{
		$target = $this->mob->getTargetEntity();
		if ($target === null) {
			return false;
		}
		if (!$target->isAlive()) {
			return false;
		}

		$this->target = $target;

		return true;
	}

	public function canContinueToUse() : bool{
		return $this->canUse() || $this->target->isAlive() && !$this->mob->getNavigation()->isDone();
	}

	public function stop() : void{
		unset($this->target);

		$this->seeTime = 0;
		$this->attackTime = -1;
	}

	public function requiresUpdateEveryTick() : bool{
		return true;
	}

	public function tick() : void{
		$distanceSqr = $this->mob->getPosition()->distanceSquared($this->target->getPosition());
		$canSee = $this->mob->getSensing()->canSee($this->target);
		if ($canSee) {
			$this->seeTime++;
		} else {
			$this->seeTime = 0;
		}

		if (!($distanceSqr > $this->attackRadiusSquared) && $this->seeTime >= 5) {
			$this->mob->getNavigation()->stop();
		} else {
			$this->mob->getNavigation()->moveToEntity($this->target, $this->speedModifier);
		}

		$this->mob->getLookControl()->setLookAt($this->target, 30, 30);

		if (--$this->attackTime === 0) {
			if (!$canSee) {
				return;
			}

			$distancePercentage = sqrt($distanceSqr) / $this->attackRadius;
			$this->mob->performRangedAttack($this->target, Utils::clamp($distancePercentage, 0.1, 1));

			$this->attackTime = (int) floor($distancePercentage * ($this->maxAttackInterval - $this->minAttackInterval) + $this->minAttackInterval);
		} elseif ($this->attackTime < 0) {
			$this->attackTime = (int) floor(Utils::lerp(
				sqrt($distanceSqr) / $this->attackRadius, $this->minAttackInterval, $this->maxAttackInterval
			));
		}
	}
}
