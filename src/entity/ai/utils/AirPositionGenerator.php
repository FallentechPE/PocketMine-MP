<?php

namespace pocketmine\entity\ai\utils;

use pocketmine\block\Water;
use pocketmine\entity\PathfinderMob;
use pocketmine\math\Vector3;

/**
 * A utility class for generating random positions in air for entities.
 */
class AirPositionGenerator {

	/**
	 * Generates a random position in the air towards a given target, within the specified horizontal and vertical range.
	 *
	 * @param PathfinderMob $entity          the PathfinderMob entity that is moving towards the target
	 * @param int           $horizontalRange the maximum horizontal distance from the entity to the generated position
	 * @param int           $verticalRange   the maximum vertical distance from the entity to the generated position
	 * @param int           $yCenter         the y-coordinate around which to generate the position
	 * @param Vector3       $target          the target position to move towards
	 * @param float         $maxAngle        the maximum angle (in radians) to deviate from the direction to the target
	 *
	 * Returns a random position in the air towards the target, or null if none could be generated
	 */
	public static function getPosTowards(PathfinderMob $entity, int $horizontalRange, int $verticalRange, int $yCenter, Vector3 $target, float $maxAngle) : ?Vector3{
		$diff = $target->subtractVector($entity->getPosition());
		$isRestricted = PositionGenerator::isRestricted($entity, $horizontalRange);

		return PositionGenerator::generateRandomPosForEntity($entity,
			static function() use ($entity, $horizontalRange, $verticalRange, $yCenter, $maxAngle, $diff, $isRestricted) : ?Vector3{
				$pos = AirAndWaterPositionGenerator::generateRandomPos(
					$entity,
					$horizontalRange,
					$verticalRange,
					$yCenter,
					$diff->x,
					$diff->z,
					$maxAngle,
					$isRestricted
				);

				return ($pos !== null && !$entity->getWorld()->getBlock($pos) instanceof Water) ? $pos : null;
			}
		);
	}
}
