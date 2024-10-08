<?php

namespace pocketmine\entity\ai\sensing;

use pocketmine\entity\ai\memory\MemoryModuleType;
use pocketmine\entity\ai\targeting\TargetingConditions;
use pocketmine\entity\Living;
use pocketmine\entity\Mob;
use pocketmine\utils\Random;

abstract class Sensor {

	private int $scanRate;

	private int $timeToTick;

	public function __construct(int $rate = 20) {
		$this->scanRate = $rate;
		$this->timeToTick = (new Random())->nextBoundedInt($rate);
	}

	public function tick(Mob $entity) : void {
		$timeToTick = $this->timeToTick - 1;
		$this->timeToTick = $timeToTick;
		if ($timeToTick <= 0) {
			$this->timeToTick = $this->scanRate;
			$this->doTick($entity);
		}
	}

	abstract protected function doTick(Mob $entity) : void;

	/**
	 * @return MemoryModuleType[]
	 */
	abstract public function requires() : array;

	protected function isEntityTargetable(Mob $entity, Living $target) : bool {
		if ($entity->getBrain()->isMemoryValue(MemoryModuleType::ATTACK_TARGET(), $target)) {
			return self::TARGET_CONDITIONS_IGNORE_INVISIBILITY_TESTING()->test($entity, $target);
		}
		return self::TARGET_CONDITIONS()->test($entity, $target);
	}

	public static function TARGET_CONDITIONS_IGNORE_INVISIBILITY_TESTING() : TargetingConditions {
		return new TargetingConditions(16, false, false, true, true);
	}

	public static function TARGET_CONDITIONS() : TargetingConditions {
		return new TargetingConditions(16, false, false, true, false);
	}
}
