<?php

namespace pocketmine\entity\ai\goal;

use Closure;
use pocketmine\entity\animation\OfferFlowerAnimation;
use pocketmine\entity\animation\WithdrawFlowerAnimation;
use pocketmine\entity\Entity;
use pocketmine\entity\golem\IronGolem;
use pocketmine\entity\Villager;
use pocketmine\math\AxisAlignedBB;
use pocketmine\world\World;
use const INF;

class OfferFlowerGoal extends Goal {

	public const DEFAULT_FLOWER_OFFER_TICKS = 400;

	public const TARGET_SEARCH_RANGE = 6; //blocks

	/** @phpstan-var Closure(Entity) : bool */
	private Closure $targetValidator;

	private Entity $target;

	private int $remainingTicks = 0;

	/**
	 * @phpstan-param null|Closure(Entity) : bool $targetValidator
	 */
	public function __construct(
		protected IronGolem $mob,
		protected int $offerFlowerTicks = self::DEFAULT_FLOWER_OFFER_TICKS,
		?Closure $targetValidator = null
	) {
		$this->targetValidator = $targetValidator ?? fn(Entity $e) => $e instanceof Villager;

		$this->setFlags(Goal::FLAG_MOVE, Goal::FLAG_LOOK);
	}

	public function getTargetSearchArea() : AxisAlignedBB{
		return $this->mob->getBoundingBox()->expandedCopy(self::TARGET_SEARCH_RANGE, self::TARGET_SEARCH_RANGE, self::TARGET_SEARCH_RANGE);
	}

	public function canUse() : bool {
		$time = $this->mob->getWorld()->getTimeOfDay();
		if ($time >= World::TIME_NIGHT && $time < World::TIME_SUNRISE) { //is day check
			return false;
		}

		if ($this->mob->getRandom()->nextBoundedInt(8000) !== 0) {
			return false;
		}

		$target = null;
		$bestDistanceSquared = INF;

		foreach ($this->mob->getWorld()->getCollidingEntities($this->getTargetSearchArea(), $this->mob) as $entity) {
			if (!($this->targetValidator)($entity)) {
				continue;
			}

			$distanceSquared = $entity->getPosition()->distanceSquared($this->mob->getPosition());
			if ($distanceSquared < $bestDistanceSquared) {
				$target = $entity;
				$bestDistanceSquared = $distanceSquared;
			}
		}

		if ($target !== null) {
			$this->target = $target;

			return true;
		}

		return false;
	}

	public function canContinueToUse() : bool {
		return $this->remainingTicks > 0;
	}

	public function start() : void {
		$this->remainingTicks = $this->adjustedTickDelay($this->offerFlowerTicks);

		$this->mob->broadcastAnimation(new OfferFlowerAnimation($this->mob, $this->offerFlowerTicks));
	}

	public function stop() : void {
		unset($this->target);

		$this->mob->broadcastAnimation(new WithdrawFlowerAnimation($this->mob));
	}

	public function tick() : void {
		$this->mob->getLookControl()->setLookAt($this->target, 30, 30);
	}
}
