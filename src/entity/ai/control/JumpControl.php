<?php

namespace pocketmine\entity\ai\control;

use pocketmine\entity\Mob;

class JumpControl implements Control {

	protected Mob $mob;

	protected bool $jump = false;

	public function __construct(Mob $mob) {
		$this->mob = $mob;
	}

	public function jump() : void {
		$this->jump = true;
	}

	public function tick() : void {
		if ($this->jump) {
			$this->mob->jump();
		}
		$this->jump = false;
	}
}
