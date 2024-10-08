<?php

namespace pocketmine\entity\ai\behavior;

use pocketmine\entity\ai\memory\MemoryModuleType;
use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\entity\Mob;
use pocketmine\math\Vector3;
use function is_array;

class EntityTracker implements PosTracker {

	protected Entity $target;

	protected bool $trackEyeHeight;

	public function __construct(Entity $target, bool $trackEyeHeight) {
		$this->target = $target;
		$this->trackEyeHeight = $trackEyeHeight;
	}

	public function getTarget() : Entity{
		return $this->target;
	}

	public function currentPosition() : Vector3{
		return $this->trackEyeHeight ? $this->target->getEyePos() : $this->target->getPosition();
	}

	public function currentBlockPosition() : Vector3{
		return $this->target->getPosition()->floor();
	}

	public function isVisibleBy(Mob $entity) : bool{
		if ($this->target instanceof Living) {
			if (!$this->target->isAlive()) {
				return false;
			}

			$visibleEntitiesMemory = $entity->getBrain()->getMemory(MemoryModuleType::VISIBLE_LIVING_ENTITIES());
			return is_array($visibleEntitiesMemory) && isset($visibleEntitiesMemory[$this->target->getId()]);
		}
		return true;
	}
}
