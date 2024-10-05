<?php

namespace pocketmine\block\utils;

use pocketmine\data\runtime\RuntimeDataDescriber;

trait EggTrait{

	protected CrackedState $crackedState = CrackedState::NO_CRACKS;


	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->enum($this->crackedState);
	}

	public function getEggCrackedState() : CrackedState{ return $this->crackedState; }

	/** @return $this */
	public function setEggCrackedState(CrackedState $crackedState) : self{
		$this->crackedState = $crackedState;
		return $this;
	}


}