<?php

namespace pocketmine\entity\ai\utils;

use pocketmine\entity\pathfinder\evaluator\WalkNodeEvaluator;
use pocketmine\entity\PathfinderMob;
use pocketmine\math\Vector3;

/**
 * A utility class for generating random positions in air and water for entities.
 */
class AirAndWaterPositionGenerator {

	/**
	 * @param PathfinderMob $entity          the entity for which the position is generated
	 * @param int           $horizontalRange the maximum horizontal distance from the entity
	 * @param int           $verticalRange   the maximum vertical distance from the entity
	 * @param int           $yCenter         the center y coordinate for the position
	 * @param float         $directionX      the x component of the direction towards which the position is generated
	 * @param float         $directionZ      the z component of the direction towards which the position is generated
	 * @param float         $maxAngle        the maximum angle, in radians, between the generated position and the direction
	 *
	 * Returns a random position in air or water towards the specified direction, or null if no position could be generated
	 */
	public static function getPos(PathfinderMob $entity, int $horizontalRange, int $verticalRange, int $yCenter, float $directionX, float $directionZ, float $maxAngle) : ?Vector3{
		$isRestricted = PositionGenerator::isRestricted($entity, $horizontalRange);
		return PositionGenerator::generateRandomPosForEntity($entity,
			fn() : ?Vector3 => static::generateRandomPos($entity, $horizontalRange, $verticalRange, $yCenter, $directionX, $directionZ, $maxAngle, $isRestricted)
		);
	}

	/**
	 * @param PathfinderMob $entity          the entity for which the position is generated
	 * @param int           $horizontalRange the maximum horizontal distance from the entity
	 * @param int           $verticalRange   the maximum vertical distance from the entity
	 * @param int           $yCenter         the center y coordinate for the position
	 * @param float         $directionX      the x component of the direction towards which the position is generated
	 * @param float         $directionZ      the z component of the direction towards which the position is generated
	 * @param float         $maxAngle        the maximum angle, in radians, between the generated position and the direction
	 * @param bool          $isRestricted    whether the entity is restricted from moving to certain positions
	 *
	 * Returns a random position air or water towards the specified direction, or null if no position could be generated
	 */
	public static function generateRandomPos(PathfinderMob $entity, int $horizontalRange, int $verticalRange, int $yCenter, float $directionX, float $directionZ, float $maxAngle, bool $isRestricted) : ?Vector3{
		$direction = PositionGenerator::generateRandomDirectionWithinRadians($entity->getRandom(), $horizontalRange, $verticalRange, $yCenter, $directionX, $directionZ, $maxAngle);
		if ($direction === null) {
			return null;
		}

		$pos = PositionGenerator::generateRandomPosTowardDirection($entity, $horizontalRange, $entity->getRandom(), $direction);
		$world = $entity->getWorld();

		if ($world->isInWorld((int) $pos->x, (int) $pos->y, (int) $pos->z) &&
			!($isRestricted && $entity->isWithinRestriction($pos))
		) {
			$pos = PositionGenerator::moveUpOutOfSolid($pos, $world->getMaxY(),
				static fn($position) : bool => $world->getBlock($position)->isSolid()
			);

			return ($entity->getPathfindingMalus(WalkNodeEvaluator::getBlockPathTypeStatic($world, (int) $pos->x, (int) $pos->y, (int) $pos->z)) === 0.0
			) ? $pos : null;
		}

		return null;
	}
}
