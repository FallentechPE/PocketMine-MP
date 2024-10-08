<?php

namespace pocketmine\entity\ai\utils;

use pocketmine\block\Water;
use pocketmine\entity\pathfinder\evaluator\WalkNodeEvaluator;
use pocketmine\entity\PathfinderMob;
use pocketmine\math\Vector3;

/**
 * A utility class for generating random positions for entitities to hover towards.
 */
class HoverPositionGenerator {

	/**
	 * @param PathfinderMob $entity     The entity for which to generate the position.
	 * @param int           $xzRadius   The maximum distance from the entity in the horizontal plane.
	 * @param int           $yRadius    The maximum distance from the entity in the vertical axis.
	 * @param float         $directionX The x component of the direction towards which the position is generated.
	 * @param float         $directionZ The z component of the direction towards which the position is generated.
	 * @param float         $angle      The angle spread, in radians.
	 * @param int           $minHeight  The minimum height above the ground for the generated position.
	 * @param int           $maxHeight  The maximum height above the ground for the generated position.
	 *
	 * Returns a random position towards a given direction, or null if none could be generated.
	 */
	public static function getPos(PathfinderMob $entity, int $xzRadius, int $yRadius, float $directionX, float $directionZ, float $angle, int $minHeight, int $maxHeight) : ?Vector3{
		$isRestricted = PositionGenerator::isRestricted($entity, $xzRadius);

		return PositionGenerator::generateRandomPosForEntity($entity, static function() use ($entity, $xzRadius, $yRadius, $directionX, $directionZ, $angle, $minHeight, $maxHeight, $isRestricted) : ?Vector3{
			$direction = PositionGenerator::generateRandomDirectionWithinRadians(
				$entity->getRandom(), $xzRadius, $yRadius, 0, $directionX, $directionZ, $angle
			);
			if ($direction === null) {
				return null;
			}

			$pos = LandPositionGenerator::generateRandomPosTowardDirection($entity, $xzRadius, $isRestricted, $direction);
			if ($pos === null) {
				return null;
			}

			$world = $entity->getWorld();
			$pos = PositionGenerator::moveUpToAboveSolid($pos,
				$entity->getRandom()->nextBoundedInt($maxHeight - $minHeight + 1) + $minHeight, $entity->getWorld()->getMaxY(),
				static fn($position) : bool => $world->getBlock($position)->isSolid()
			);
			return (!$world->getBlock($pos) instanceof Water &&
				$entity->getPathfindingMalus(WalkNodeEvaluator::getBlockPathTypeStatic($world, (int) $pos->x, (int) $pos->y, (int) $pos->z)) === 0.0
			) ? $pos : null;
		});
	}
}
