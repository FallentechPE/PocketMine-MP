<?php

namespace pocketmine\entity\ai\utils;

use pocketmine\entity\pathfinder\evaluator\WalkNodeEvaluator;
use pocketmine\entity\PathfinderMob;
use pocketmine\math\Vector3;
use const M_PI_2;

/**
 * A utility class for generating random positions where entities can move to.
 */
class DefaultPositionGenerator {

	/**
	 * Generates a random position within the specified range for the given entity to move to.
	 * The entity may be constrained by its movement properties.
	 *
	 * @param PathfinderMob $entity          The entity for which to generate a random position.
	 * @param int           $horizontalRange The maximum horizontal range from the entity's current position.
	 * @param int           $verticalRange   The maximum vertical range from the entity's current position.
	 *
	 * Returns a Vector3 representing the random position, or null if none was found.
	 */
	public static function getPos(PathfinderMob $entity, int $horizontalRange, int $verticalRange) : ?Vector3{
		$isRestricted = PositionGenerator::isRestricted($entity, $horizontalRange);

		return PositionGenerator::generateRandomPosForEntity($entity,
			static function() use ($entity, $horizontalRange, $verticalRange, $isRestricted) : ?Vector3{
				$pos = PositionGenerator::generateRandomDirection($entity->getRandom(), $horizontalRange, $verticalRange);
				return self::generateRandomPosTowardDirection($entity, $horizontalRange, $isRestricted, $pos);
			}
		);
	}

	/**
	 * Generates a random position towards the specified target within the specified range for the given entity to move to.
	 * The entity may be constrained by its movement properties.
	 *
	 * @param PathfinderMob $entity          The entity for which to generate a random position.
	 * @param int           $horizontalRange The maximum horizontal range from the entity's current position.
	 * @param int           $verticalRange   The maximum vertical range from the entity's current position.
	 * @param Vector3       $target          The target towards which the entity should move.
	 * @param float         $maxAngle        The maximum angle between the entity's current direction and the direction towards the target.
	 *
	 * Returns a Vector3 representing the random position, or null if none was found.
	 */
	public static function getPosTowards(PathfinderMob $entity, int $horizontalRange, int $verticalRange, Vector3 $target, float $maxAngle) : ?Vector3{
		$diff = $target->subtractVector($entity->getPosition());
		$isRestricted = PositionGenerator::isRestricted($entity, $horizontalRange);

		return PositionGenerator::generateRandomPosForEntity($entity,
			static function() use ($entity, $horizontalRange, $verticalRange, $maxAngle, $diff, $isRestricted) : ?Vector3{
				$direction = PositionGenerator::generateRandomDirectionWithinRadians($entity->getRandom(), $horizontalRange, $verticalRange, 0, $diff->x, $diff->z, $maxAngle);
				return $direction === null ? null : self::generateRandomPosTowardDirection($entity, $horizontalRange, $isRestricted, $direction);
			}
		);
	}

	/**
	 * Generates a random position away from the specified location within the specified range for the given entity to move to.
	 * The entity may be constrained by its movement properties.
	 *
	 * @param PathfinderMob $entity          The entity for which to generate a random position.
	 * @param int           $horizontalRange The maximum horizontal range from the entity's current position.
	 * @param int           $verticalRange   The maximum vertical range from the entity's current position.
	 * @param Vector3       $awayFrom        The location from which the entity should move away.
	 *
	 * Returns a Vector3 representing the random position, or null if none was found.
	 */
	public static function getPosAway(PathfinderMob $entity, int $horizontalRange, int $verticalRange, Vector3 $awayFrom) : ?Vector3{
		$diff = $entity->getPosition()->subtractVector($awayFrom);
		$isRestricted = PositionGenerator::isRestricted($entity, $horizontalRange);

		return PositionGenerator::generateRandomPosForEntity($entity,
			static function() use ($entity, $horizontalRange, $verticalRange, $diff, $isRestricted) : ?Vector3{
				$direction = PositionGenerator::generateRandomDirectionWithinRadians($entity->getRandom(), $horizontalRange, $verticalRange, 0, $diff->x, $diff->z, M_PI_2);
				return $direction === null ? null : self::generateRandomPosTowardDirection($entity, $horizontalRange, $isRestricted, $direction);
			}
		);
	}

	/**
	 * Generates a random position towards the specified direction for the given entity to move to.
	 * The entity may be constrained by its movement properties.
	 *
	 * @param PathfinderMob $entity          The entity for which to generate a random position.
	 * @param int           $horizontalRange The maximum horizontal range from the entity's current position.
	 * @param bool          $isConstrained   Whether the entity is constrained by its movement goals.
	 * @param Vector3       $direction       The direction towards which the entity should move.
	 *
	 * Returns Vector3 representing the random position, or null if none was found.
	 */
	private static function generateRandomPosTowardDirection(PathfinderMob $entity, int $horizontalRange, bool $isConstrained, Vector3 $direction) : ?Vector3{
		$pos = PositionGenerator::generateRandomPosTowardDirection($entity, $horizontalRange, $entity->getRandom(), $direction);
		return ($entity->getWorld()->isInWorld((int) $pos->x, (int) $pos->y, (int) $pos->z) &&
			!($isConstrained && $entity->isWithinRestriction($pos)) &&
			$entity->getNavigation()->isStableDestination($pos) &&
			$entity->getPathfindingMalus(WalkNodeEvaluator::getBlockPathTypeStatic($entity->getWorld(), (int) $pos->x, (int) $pos->y, (int) $pos->z)) === 0.0
		) ? $pos : null;
	}
}
