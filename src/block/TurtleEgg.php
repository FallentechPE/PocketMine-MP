<?php

namespace pocketmine\block;

use pocketmine\data\runtime\RuntimeDataDescriber;

class TurtleEgg extends Flowable{
	public const MIN_COUNT = 1;
	public const MAX_COUNT = 4;
	public const MIN_CRACKS = 1;
	public const MAX_CRACKS = 3;

	protected int $count = self::MIN_COUNT;
	protected int $cracks = self::MIN_CRACKS;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->boundedIntAuto(self::MIN_COUNT, self::MAX_COUNT, $this->count);
		$w->boundedIntAuto(self::MIN_CRACKS, self::MAX_CRACKS, $this->cracks);
	}

	public function getCount() : int{ return $this->count; }

	/** @return $this */
	public function setCount(int $count) : self{
		if($count < self::MIN_COUNT || $count > self::MAX_COUNT){
			throw new \InvalidArgumentException("Count must be in range " . self::MIN_COUNT . " ... " . self::MAX_COUNT);
		}
		$this->count = $count;
		return $this;
	}

	public function getCracks() : int{
		return $this->cracks;
	}

	/** @return $this */
	public function setCracks(int $cracks) : self{
		$this->cracks = $cracks;
		return $this;
	}


}