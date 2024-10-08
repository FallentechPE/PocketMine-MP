<?php

namespace pocketmine\entity\ai\goal\enderman;

use pocketmine\block\BlockTypeIds;
use pocketmine\block\VanillaBlocks;
use pocketmine\entity\ai\goal\Goal;
use pocketmine\entity\monster\Enderman;
use pocketmine\math\Vector3;
use pocketmine\math\VoxelRayTrace;
use function floor;

class TakeBlockGoal extends Goal {

	public function __construct(
		protected Enderman $entity
	) { }

	public function canUse() : bool{
		if ($this->entity->getCarriedBlock() !== null) {
			return false;
		}

		//TODO: Mob griefing gamerule check

		return $this->entity->getRandom()->nextBoundedInt($this->reducedTickDelay(20)) === 0;
	}

	public function start() : void{
		$this->entity->getNavigation()->stop();
	}

	public function tick() : void{
		$world = $this->entity->getWorld();
		$entityPos = $this->entity->getPosition();

		$random = $this->entity->getRandom();
		$targetPos = new Vector3(
			floor($entityPos->getX() - 2 + $random->nextFloat() * 4),
			floor($entityPos->getY() + $random->nextFloat() * 3),
			floor($entityPos->getZ() - 2 + $random->nextFloat() * 4)
		);

		$targetBlock = $world->getBlock($targetPos);
		if (!Enderman::isHoldableBlock($targetBlock)) {
			return;
		}

		foreach(VoxelRayTrace::betweenPoints($this->entity->getEyePos(), $targetPos->add(0.5, 0.5, 0.5)) as $vector3){
			if ($vector3->equals($targetPos)) {
				break;
			}
			$block = $world->getBlockAt((int) $vector3->x, (int) $vector3->y, (int) $vector3->z);
			if ($block->getTypeId() !== BlockTypeIds::AIR) {
				return;
			}
		}

		$world->setBlock($targetPos, VanillaBlocks::AIR());
		$this->entity->setCarriedBlock($targetBlock);
	}
}
