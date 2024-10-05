<?php

namespace pocketmine\block;

use pocketmine\data\runtime\RuntimeDataDescriber;

class RespawnAnchor extends Opaque{

	public const MIN_CHARGE = 0;
	public const MAX_CHARGE = 4;

	protected int $charge = self::MIN_CHARGE;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->boundedIntAuto(self::MIN_CHARGE, self::MAX_CHARGE, $this->charge);
	}

	/** @return $this */
	public function setCharge(int $charge) : self {
		$this->charge = $charge;
		return $this;
	}


	public function getCharge() : int{
		return $this->charge;
	}

	public function getLightLevel() : int{
		return match ($this->charge) {
			1 => 3,
			2 => 7,
			3 => 11,
			4 => 15,
			default => 0
		};
	}

}