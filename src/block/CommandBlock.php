<?php

namespace pocketmine\block;

use pocketmine\block\utils\AnyFacingTrait;
use pocketmine\data\runtime\RuntimeDataDescriber;

class CommandBlock extends Opaque{
	use AnyFacingTrait;

	protected bool $conditional = false;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->facing($this->facing);
		$w->bool($this->conditional);
	}

	public function isConditional() : bool{
		return $this->conditional;
	}

	/** @return $this */
	public function setConditional(bool $conditional) : self {
		$this->conditional = $conditional;
		return $this;
	}


}