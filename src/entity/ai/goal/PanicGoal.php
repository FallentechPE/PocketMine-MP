<?php

namespace pocketmine\entity\ai\goal;

use pocketmine\block\BlockTypeIds;
use pocketmine\entity\ai\utils\DefaultPositionGenerator;
use pocketmine\entity\Entity;
use pocketmine\entity\PathfinderMob;
use pocketmine\math\Vector3;
use pocketmine\utils\Utils;
use function count;

class PanicGoal extends Goal {

	public const WATER_CHECK_VERTICAL_DISTANCE = 1;

	protected Vector3 $target;

	protected bool $isRunning;

	public function __construct(
		protected PathfinderMob $entity,
		protected float $speedModifier
	) {
		$this->setFlags(Goal::FLAG_MOVE);
	}

	public function canUse() : bool {
		if (!$this->shouldPanic()) {
			return false;
		}

		//Actually this behavior only happens in java, but it's a cool feature B)
		if ($this->entity->isOnFire()) {
			$target = $this->lookForWater($this->entity, 5);
			if ($target !== null) {
				$this->target = $target;
				return true;
			}
		}

		$target = $this->findRandomPosition();
		if ($target !== null) {
			$this->target = $target;
			return true;
		}

		return false;
	}

	protected function shouldPanic() : bool{
		//TODO: check if it is freezing
		return $this->entity->getExpirableLastDamageByEntity() !== null || $this->entity->isOnFire();
	}

	public function isRunning() : bool{
		return $this->isRunning;
	}

	protected function findRandomPosition() : ?Vector3 {
		return DefaultPositionGenerator::getPos($this->entity, 5, 4);
	}

	protected function lookForWater(Entity $entity, int $horizontalRange) : ?Vector3 {
		$world = $this->entity->getWorld();
		$ePosition = $entity->getPosition()->floor();

		if (count($world->getBlock($ePosition)->getCollisionBoxes()) > 0) {
			return null;
		}

		foreach (Utils::getAdjacentPositions($ePosition, $horizontalRange, 1, $horizontalRange) as $pos) {
			if ($world->getBlock($pos)->getTypeId() === BlockTypeIds::WATER) {
				return $pos;
			}
		}
		return null;
	}

	public function start() : void{
		$this->entity->getNavigation()->moveToXYZ($this->target->x, $this->target->y, $this->target->z, $this->speedModifier);
	}

	public function stop() : void {
		$this->isRunning = false;
	}

	public function canContinueToUse() : bool {
		return !$this->entity->getNavigation()->isDone();
	}
}
