<?php

namespace pocketmine\block;

use pocketmine\data\runtime\RuntimeDataDescriber;

class SculkShrieker extends Transparent{


	protected bool $active = false;
	protected bool $summon = false;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->bool($this->active);
		$w->bool($this->summon);
	}

	public function isActive() : bool {
		return $this->active;
	}

	/** @return $this */
	public function setActive(bool $active) : self {
		$this->active = $active;
		return $this;
	}

	public function canSummon() : bool{
		return $this->summon;
	}

	/** @return $this */
	public function setSummon(bool $summon) : self{
		$this->summon = $summon;
		return $this;
	}

}