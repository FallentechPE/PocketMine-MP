<?php

namespace pocketmine\entity\ai\goal;

use pocketmine\entity\ai\targeting\TargetingConditions;
use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\entity\Mob;
use pocketmine\utils\Utils;
use function array_reduce;

class LookAtEntityGoal extends Goal {

	public const DEFAULT_PROBABILITY = 0.02;

	protected TargetingConditions $targetConditions;

	protected ?Living $lookingAt = null;

	protected int $lookTime;

	/**
	 * @phpstan-param class-string<Living> $targetType
	 */
	public function __construct(
		protected Mob $entity,
		protected string $targetType,
		protected float $lookDistance,
		protected float $probability = self::DEFAULT_PROBABILITY,
		protected bool $onlyHorizontal = false
	) {
		Utils::testValidInstance($targetType, Living::class);

		//TODO: add condition for no passengers riding
		$this->targetConditions = (new TargetingConditions($lookDistance))
			->allowInvulnerable()
			->allowNonAttackable();

		$this->setFlags(Goal::FLAG_LOOK);
	}

	public function canUse() : bool{
		if ($this->entity->getRandom()->nextFloat() >= $this->probability) {
			return false;
		}

		$pos = $this->entity->getEyePos();
		$this->lookingAt = array_reduce($this->entity->getWorld()->getNearbyEntities(
			$this->entity->getBoundingBox()->expandedCopy($this->lookDistance, $this->lookDistance, $this->lookDistance)
		), function(?Living $carry, Entity $current) use ($pos) : ?Living {
			if (!$current instanceof $this->targetType || !$this->targetConditions->test($this->entity, $current)) {
				return $carry;
			}

			return ($carry !== null &&
				$carry->getPosition()->distanceSquared($pos) < $current->getPosition()->distanceSquared($pos)
			) ? $carry : $current;
		});

		return $this->lookingAt !== null;
	}

	public function canContinueToUse() : bool{
		if ($this->lookingAt === null ||
			!$this->lookingAt->isAlive() ||
			$this->entity->getEyePos()->distanceSquared($this->lookingAt->getPosition()) > $this->lookDistance ** 2
		) {
			return false;
		}

		return $this->lookTime > 0;
	}

	public function start() : void{
		$this->lookTime = $this->adjustedTickDelay(40 + $this->entity->getRandom()->nextBoundedInt(40));
	}

	public function stop() : void{
		$this->lookingAt = null;
	}

	public function tick() : void{
		/** @var Living $looking */
		$looking = $this->lookingAt;

		$lookAt = $looking->getEyePos();
		if ($this->onlyHorizontal) {
			$lookAt->y = $this->entity->getPosition()->y + $this->entity->getEyeHeight();
		}

		$this->entity->getLookControl()->setLookAt($lookAt);
		$this->lookTime--;
	}
}
