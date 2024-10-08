<?php

namespace pocketmine\entity\ai\targeting;

use Closure;
use pocketmine\entity\Living;
use pocketmine\entity\Mob;
use pocketmine\player\Player;
use function max;

class TargetingConditions {

	/**
	 * @phpstan-param null|Closure(Living) : bool $validator
	 */
	public function __construct(
		protected float $range = -1,
		protected bool $allowInvulnerable = false,
		protected bool $allowUnseeable = false,
		protected bool $allowNonAttackable = false,
		protected bool $testInvisible = true,
		protected ?Closure $validator = null
	) {
	}

	public function setRange(float $range) : self{
		$this->range = $range;
		return $this;
	}

	/**
	 * @phpstan-param null|Closure(Living) : bool $validator
	 */
	public function setValidator(?Closure $validator) : self{
		$this->validator = $validator;
		return $this;
	}

	public function allowInvulnerable(bool $value = true) : self{
		$this->allowInvulnerable = $value;
		return $this;
	}

	public function allowUnseeable(bool $value = true) : self{
		$this->allowUnseeable = $value;
		return $this;
	}

	public function allowNonAttackable(bool $value = true) : self{
		$this->allowNonAttackable = $value;
		return $this;
	}

	public function testInvisible(bool $value = true) : self{
		$this->testInvisible = $value;
		return $this;
	}

	public function test(?Living $entity, Living $target) : bool {
		if ($entity === $target) {
			return false;
		}
		if ($target instanceof Player && $target->isSpectator()) {
			return false;
		}
		if (!$target->isAlive()) {
			return false;
		}
		if (!$this->allowInvulnerable && ($target instanceof Player && $target->isCreative())) {
			return false;
		}
		if ($this->validator !== null && !($this->validator)($target)) {
			return false;
		}
		if ($entity !== null) {
			if (!$this->allowNonAttackable) {
				if ($entity instanceof Living && !$entity->canAttack($target)) {
					return false;
				}
			}
			if ($this->range > 0) {
				$percent = $this->testInvisible ? TargetingUtils::getVisibilityPercent($target, $entity) : 1.0;
				$visibility = max($this->range * $percent, 2.0);
				$distanceSquare = $entity->getLocation()->distanceSquared($target->getLocation());
				if ($distanceSquare > $visibility * $visibility) {
					return false;
				}
			}
			if (!$this->allowUnseeable && $entity instanceof Mob && !$entity->getSensing()->canSee($target)) {
				return false;
			}
		}
		return true;
	}
}
