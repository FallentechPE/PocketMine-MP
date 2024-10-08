<?php

namespace pocketmine\entity\ai\goal\slime;

use pocketmine\entity\ai\goal\Goal;
use pocketmine\entity\Living;
use pocketmine\entity\monster\Slime;

class SlimeAttackGoal extends Goal {

	public const DEFAULT_ATTACK_COOLDOWN = 10;

	protected int $growTiredTimer;

	protected int $attackTimer = 0;

	public function __construct(protected Slime $mob, protected int $attackCooldown = self::DEFAULT_ATTACK_COOLDOWN) {
		$this->setFlags(Goal::FLAG_LOOK);
	}

	public function canUse() : bool{
		$target = $this->mob->getTargetEntity();
		if (!$target instanceof Living) {
			return false;
		}

		return $this->mob->canAttack($target);
	}

	public function start() : void{
		$this->growTiredTimer = $this->reducedTickDelay(300);

		parent::start();
	}

	public function canContinueToUse() : bool{
		$target = $this->mob->getTargetEntity();
		if (!$target instanceof Living) {
			return false;
		}

		if (!$this->mob->canAttack($target)) {
			return false;
		}

		return --$this->growTiredTimer > 0;
	}

	public function requiresUpdateEveryTick() : bool{
		return true;
	}

	public function tick() : void{
		$this->attackTimer--;

		$target = $this->mob->getTargetEntity();
		if ($target !== null) {
			$this->mob->getLookControl()->setLookAt($target, 10, 10);

			if ($this->attackTimer <= 0 &&
				$this->mob->getAttackDamage() > 0 &&
				($bb = $this->mob->getBoundingBox())->intersectsWith($target->getBoundingBox())
			) {
				$attackValidator = $this->mob->getAttackableValidator();
				foreach ($this->mob->getWorld()->getCollidingEntities($bb, $this->mob) as $entity) {
					if ($entity instanceof Living && $attackValidator($entity)) {
						$this->mob->attackEntity($entity);
					}
				}

				$this->attackTimer = $this->attackCooldown;
			}
		}

		$this->mob->getMoveControl()->setDirection($this->mob->getLocation()->getYaw(), true);
	}
}
