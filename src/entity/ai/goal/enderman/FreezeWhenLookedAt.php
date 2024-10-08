<?php

namespace pocketmine\entity\ai\goal\enderman;

use pocketmine\entity\ai\goal\Goal;
use pocketmine\entity\monster\Enderman;
use pocketmine\player\Player;

class FreezeWhenLookedAt extends Goal {

	protected Player $target;

	public function __construct(
		protected Enderman $entity
	) {
		$this->setFlags(Goal::FLAG_MOVE, Goal::FLAG_JUMP);
	}

	public function canUse() : bool{
		$target = $this->entity->getTargetEntity();
		if (!$target instanceof Player) {
			return false;
		}

		if ($this->entity->getLocation()->distanceSquared($target->getLocation()) > 16 ** 2) {
			return false;
		}

		if ($this->entity->isLookingAtMe($target)) {
			$this->target = $target;
			return true;
		}

		return false;
	}

	public function start() : void{
		$this->entity->getNavigation()->stop();
	}

	public function tick() : void{
		$this->entity->getLookControl()->setLookAt($this->target);
	}
}
