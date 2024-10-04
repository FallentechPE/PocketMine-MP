<?php

namespace pocketmine\block;

use pocketmine\data\runtime\RuntimeDataDescriber;

class SculkSensor extends Transparent{

	public const MIN_PHASE = 0;
	public const MAX_PHASE = 2;

	protected int $phase = self::MIN_PHASE;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->boundedIntAuto(self::MIN_PHASE, self::MAX_PHASE, $this->phase);
	}

	public function getPhase() : int{ return $this->phase; }

	/** @return $this */
	public function setPhase(int $phase) : self{
		if($phase < self::MIN_PHASE || $phase > self::MAX_PHASE){
			throw new \InvalidArgumentException("Count must be in range " . self::MIN_PHASE . " ... " . self::MAX_PHASE);
		}
		$this->phase = $phase;
		return $this;
	}

	public function getLightLevel() : int{
		return 1;
	}

}