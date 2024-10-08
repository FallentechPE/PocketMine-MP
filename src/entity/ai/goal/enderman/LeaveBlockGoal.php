<?php

namespace pocketmine\entity\ai\goal\enderman;

use pocketmine\entity\ai\goal\Goal;
use pocketmine\entity\monster\Enderman;
use pocketmine\math\Vector3;
use function floor;

class LeaveBlockGoal extends Goal {

	public function __construct(
		protected Enderman $entity
	) { }

	public function canUse() : bool{
		if ($this->entity->getCarriedBlock() === null) {
			return false;
		}

		//TODO: Mob griefing gamerule check

		return $this->entity->getRandom()->nextBoundedInt($this->reducedTickDelay(2000)) === 0;
	}

	public function start() : void{
		$this->entity->getNavigation()->stop();
	}

	public function tick() : void{
		$carriedBlock = $this->entity->getCarriedBlock();
		if ($carriedBlock === null) {
			return;
		}

		$entityPos = $this->entity->getPosition();

		$random = $this->entity->getRandom();
		$pos = new Vector3(
			floor($entityPos->getX() - 1 + $random->nextFloat() * 2),
			floor($entityPos->getY() + $random->nextFloat() * 2),
			floor($entityPos->getZ() - 1 + $random->nextFloat() * 2)
		);

		if ($this->entity->placeBlock($pos)) {
			$this->entity->setCarriedBlock(null);
		}
	}
}
