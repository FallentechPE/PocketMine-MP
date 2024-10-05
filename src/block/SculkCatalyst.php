<?php

namespace pocketmine\block;

use pocketmine\data\runtime\RuntimeDataDescriber;

class SculkCatalyst extends Opaque{

	protected bool $bloom = false;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->bool($this->bloom);
	}

	/** @return $this */
	public function setBloom(bool $bloom) : self	{
		$this->bloom = $bloom;
		return $this;
	}

	public function getBloom() : bool {
		return $this->bloom;
	}

	public function getLightLevel() : int{
		return 6;
	}



}