<?php

namespace pocketmine\entity\ai\behavior;

use pocketmine\entity\Mob;
use pocketmine\math\Vector3;

interface PosTracker {

	public function currentPosition() : Vector3;

	public function currentBlockPosition() : Vector3;

	public function isVisibleBy(Mob $entity) : bool;
}
