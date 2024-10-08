<?php

namespace pocketmine\entity\ai\navigation;

use pocketmine\block\BlockTypeIds;
use pocketmine\block\Water;
use pocketmine\entity\pathfinder\BlockPathTypes;
use pocketmine\entity\pathfinder\evaluator\WalkNodeEvaluator;
use pocketmine\entity\pathfinder\Path;
use pocketmine\entity\pathfinder\PathFinder;
use pocketmine\math\Vector3;
use pocketmine\world\World;
use function floor;

class GroundPathNavigation extends PathNavigation{

	private bool $avoidSun = false;

	protected function createPathFinder(int $maxVisitedNodes) : PathFinder{
		$this->nodeEvaluator = new WalkNodeEvaluator();
		$this->nodeEvaluator->setCanPassDoors();

		return new PathFinder($this->nodeEvaluator, $maxVisitedNodes);
	}

	public function canUpdatePath() : bool{
		return $this->mob->isOnGround() || $this->isInLiquid()/* || $this->mob->isPassenger()*/;
	}

	public function getTempMobPosition() : Vector3 {
		$mobPosition = $this->mob->getPosition();
		return new Vector3($mobPosition->getX(), $this->getSurfaceY(), $mobPosition->getZ());
	}

	public function createPathToPosition(Vector3 $position, int $maxVisitedNodes, ?float $range = null) : ?Path{
		$world = $this->getWorld();
		if ($world->getBlock($position)->getTypeId() === BlockTypeIds::AIR) {
			$currentPos = $position->down();

			while ($currentPos->y > World::Y_MIN && $world->getBlock($currentPos)->getTypeId() === BlockTypeIds::AIR) {
				$currentPos = $currentPos->down();
			}

			if ($currentPos->getY() > World::Y_MIN) {
				return parent::createPathToPosition($currentPos->up(), $maxVisitedNodes, $range);
			}

			while($currentPos->getY() < World::Y_MAX && $world->getBlock($currentPos)->getTypeId() === BlockTypeIds::AIR) {
				$currentPos = $currentPos->up();
			}

			$position = $currentPos;
		}

		if (!$world->getBlock($position)->isSolid()) {
			return parent::createPathToPosition($position, $maxVisitedNodes, $range);
		}

		$currentPos = $position->up();

		while($currentPos->getY() < World::Y_MAX && $world->getBlock($currentPos)->isSolid()) {
			$currentPos = $currentPos->up();
		}

		return parent::createPathToPosition($currentPos, $maxVisitedNodes, $range);
	}

	public function getSurfaceY() : int{
		$mobPos = $this->mob->getPosition();
		if ($this->mob->isInWater() && $this->canFloat()) {
			$y = (int) $mobPos->getY();
			$world = $this->getWorld();
			$block = $world->getBlock($mobPos);
			$distDiff = 0;

			while ($block instanceof Water) {
				$block = $world->getBlockAt((int) floor($mobPos->x), ++$y, (int) floor($mobPos->z));
				if (++$distDiff > 16) {
					return (int) $mobPos->getY();
				}
			}

			return $y;
		}

		return (int) floor($mobPos->y + 0.5);
	}

	protected function trimPath() : void{
		parent::trimPath();

		if ($this->path === null) {
			return;
		}

		if ($this->avoidSun) {
			$mobPos = $this->mob->getPosition();
			$world = $this->getWorld();
			if ($world->getRealBlockSkyLightAt((int) floor($mobPos->x), (int) floor($mobPos->y + 0.5), (int) floor($mobPos->z)) >= 15) {
				return;
			}

			for($i = 0; $i < $this->path->getNodeCount(); ++$i) {
				$node = $this->path->getNode($i);
				if ($world->getRealBlockSkyLightAt($node->x(), $node->y(), $node->z()) >= 15) {
					$this->path->truncateNodes($i);
					return;
				}
			}
		}
	}

	public function hasValidPathType(BlockPathTypes $pathType) : bool{
		if ($pathType->equals(BlockPathTypes::WATER()) || $pathType->equals(BlockPathTypes::LAVA())) {
			return false;
		}
		return !$pathType->equals(BlockPathTypes::OPEN());
	}

	public function setCanOpenDoors(bool $value = true) : void{
		$this->nodeEvaluator->setCanOpenDoors($value);
	}

	public function canOpenDoors() : bool{
		return $this->nodeEvaluator->canOpenDoors();
	}

	public function setCanPassDoors(bool $value = true) : void{
		$this->nodeEvaluator->setCanPassDoors($value);
	}

	public function canPassDoors() : bool{
		return $this->nodeEvaluator->canPassDoors();
	}

	public function setAvoidSun(bool $value = true) : void{
		$this->avoidSun = $value;
	}

	public function avoidSun() : bool{
		return $this->avoidSun;
	}

	public function setCanWalkOverFences(bool $value = true) : void{
		$this->nodeEvaluator->setCanWalkOverFences($value);
	}

	public function canWalkOverFences() : bool{
		return $this->nodeEvaluator->canWalkOverFences();
	}
}
