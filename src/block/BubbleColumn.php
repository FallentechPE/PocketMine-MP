<?php

namespace pocketmine\block;

use pocketmine\data\runtime\RuntimeDataDescriber;

class BubbleColumn extends Transparent{

	protected bool $dragDown = false;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->bool($this->dragDown);
	}

	public function isDragDown() : bool{
		return $this->dragDown;
	}

	/** @return $this */
	public function setDragDown(bool $dragDown) : self{
		$this->dragDown = $dragDown;
		return $this;
	}

}