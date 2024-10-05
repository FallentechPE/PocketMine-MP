<?php

namespace pocketmine\block;

use pocketmine\data\runtime\RuntimeDataDescriber;

class TrialSpawner extends Transparent{

	public const MIN_STATE = 0;
	public const MAX_STATE = 5;

	protected bool $ominous = false;
	protected int $state = self::MIN_STATE;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->bool($this->ominous);
		$w->boundedIntAuto(self::MIN_STATE, self::MAX_STATE, $this->state);
	}

	/** @return $this */
	public function setOminous(bool $ominous) : self{
		$this->ominous = $ominous;
		return $this;
	}

	public function isOminous() : bool{
		return $this->ominous;
	}

	/** @return $this */
	public function setState(int $state) : self{
		$this->state = $state;
		return $this;
	}

	public function getState() : int{
		return $this->state;
	}

	public function getLightLevel() : int{
		return match ($this->state) {
			2 => 4,
			0 => 8,
			default => 0
		};
		// todo this cannot be right
	}

}