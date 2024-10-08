<?php

namespace pocketmine\entity\ai\behavior;

use pocketmine\entity\ai\memory\MemoryModuleType;
use pocketmine\entity\ai\memory\WalkTarget;
use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\entity\Mob;
use pocketmine\item\Item;
use pocketmine\item\Releasable;
use pocketmine\utils\Utils;
use pocketmine\world\Position;
use function is_array;

class BehaviorUtils {

	public static function lockGazeAndWalkToEachOther(Mob $entity1, Mob $entity2, float $speedModifier) : void {
		self::lookAtEachOther($entity1, $entity2);
		self::setWalkAndLookTargetMemoriesToEachOther($entity1, $entity2, $speedModifier);
	}

	private static function lookAtEachOther(Mob $entity1, Mob $entity2) : void {
		self::lookAtEntity($entity1, $entity2);
		self::lookAtEntity($entity2, $entity1);
	}

	public static function lookAtEntity(Mob $entity, Living $target) : void {
		$entity->getBrain()->setMemory(MemoryModuleType::LOOK_TARGET(), new EntityTracker($target, true));
	}

	private static function setWalkAndLookTargetMemoriesToEachOther(Mob $entity1, Mob $entity2, float $speedModifier) : void {
		$closeEnoughDist = 2;
		self::setWalkToEntityAndLookTargetMemories($entity1, $entity2, $speedModifier, $closeEnoughDist);
		self::setWalkToEntityAndLookTargetMemories($entity2, $entity1, $speedModifier, $closeEnoughDist);
	}

	public static function setWalkToEntityAndLookTargetMemories(Mob $entity, Entity $target, float $speedModifier, int $closeEnoughDist) : void {
		$walkTarget = new WalkTarget(new EntityTracker($target, false), $speedModifier, $closeEnoughDist);
		$entity->getBrain()->setMemory(MemoryModuleType::LOOK_TARGET(), new EntityTracker($target, true));
		$entity->getBrain()->setMemory(MemoryModuleType::WALK_TARGET(), $walkTarget);
	}

	public static function setWalkToPositionAndLookTargetMemories(Mob $entity, Position $target, float $speedModifier, int $closeEnoughDist) : void {
		$walkTarget = new WalkTarget(new PositionTracker($target), $speedModifier, $closeEnoughDist);
		$entity->getBrain()->setMemory(MemoryModuleType::LOOK_TARGET(), new PositionTracker($target));
		$entity->getBrain()->setMemory(MemoryModuleType::WALK_TARGET(), $walkTarget);
	}

	public static function dropItem(Mob $entity, Item $item) : void {
		$entity->getWorld()->dropItem($entity->getLocation()->add(0, $entity->getEyeHeight() - 0.3, 0), $item, $entity->getDirectionVector()->multiply(0.4), 40);
	}

	public static function isWithinAttackRange(Mob $mob, Living $target, int $range) : bool {
		$item = $mob->getInventory()->getMainHand();
		if ($item instanceof Releasable && $mob->canUseReleasable($item)) {
			return $mob->getLocation()->distanceSquared($target->getLocation()) < (Utils::getDefaultProjectileRange($item) - $range) ** 2;
		}
		return self::isWithinMeleeAttackRange($mob, $target);
	}

	public static function isWithinMeleeAttackRange(Mob $mob, Living $target) : bool {
		$distanceSquared = $mob->getLocation()->distanceSquared($target->getLocation());
		$maximumMeleeAttackDistance = ($mob->getSize()->getWidth() * 2) ** 2 + $target->getSize()->getWidth();
		return $distanceSquared <= $maximumMeleeAttackDistance;
	}

	public static function canSee(Mob $entity, Living $target) : bool {
		$brain = $entity->getBrain();
		if ($brain->hasMemoryValue(MemoryModuleType::VISIBLE_LIVING_ENTITIES())) {
			$value = $brain->getMemory(MemoryModuleType::VISIBLE_LIVING_ENTITIES());
			if ($value !== null && is_array($value)) {
				return isset($value[$target->getId()]);
			}
		}
		return false;
	}
}
