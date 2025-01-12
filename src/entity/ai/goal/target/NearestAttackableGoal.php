<?php

namespace pocketmine\entity\ai\goal\target;

use Closure;
use pocketmine\entity\ai\goal\Goal;
use pocketmine\entity\ai\targeting\TargetingConditions;
use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\entity\Mob;
use pocketmine\math\AxisAlignedBB;
use pocketmine\utils\Utils;
use function array_reduce;

class NearestAttackableGoal extends TargetGoal {

	public const DEFAULT_RANDOM_INTERVAL = 10;

	protected int $randomInterval;

	protected TargetingConditions $targetingConditions;

	/**
	 * @phpstan-param class-string<Living> $targetType
	 */
	public function __construct(
		Mob $entity,
		protected string $targetType,
		int $interval = self::DEFAULT_RANDOM_INTERVAL,
		bool $mustSee = true,
		bool $mustReach = false,
		?Closure $targetValidator = null
	) {
		if ($targetType !== Living::class) {
			Utils::testValidInstance($targetType, Living::class);
		}

		parent::__construct($entity, $mustSee, $mustReach);

		$this->randomInterval = $this->reducedTickDelay($interval);
		$this->targetingConditions = (new TargetingConditions())
			->setRange($this->getFollowDistance())
			->setValidator($targetValidator);

		$this->setFlags(Goal::FLAG_TARGET);
	}

	public function canUse() : bool{
		if ($this->randomInterval > 0 && $this->entity->getRandom()->nextBoundedInt($this->randomInterval) !== 0) {
			return false;
		}

		$this->findTarget();
		return $this->target !== null;
	}

	public function getTargetSearchArea(float $range) : AxisAlignedBB{
		return $this->entity->getBoundingBox()->expandedCopy($range, 0.4, $range);
	}

	protected function findTarget() : void{
		$pos = $this->entity->getEyePos();
		$this->target = array_reduce($this->entity->getWorld()->getCollidingEntities($this->getTargetSearchArea($this->getFollowDistance()), $this->entity),
			function(?Living $carry, Entity $current) use ($pos) : ?Living {
				if (!$current instanceof $this->targetType || !$this->targetingConditions->test($this->entity, $current)) {
					return $carry;
				}

				return ($carry !== null &&
					$carry->getPosition()->distanceSquared($pos) < $current->getPosition()->distanceSquared($pos)
				) ? $carry : $current;
			});
	}

	public function start() : void{
		$this->entity->setTargetEntity($this->target);
		parent::start();
	}

	public function setTarget(?Living $target) : void{
		$this->target = $target;
	}
}
