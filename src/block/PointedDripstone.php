<?php

namespace pocketmine\block;

use pocketmine\block\utils\DripstoneThickness;
use pocketmine\data\runtime\RuntimeDataDescriber;

class PointedDripstone extends Transparent{

	protected DripstoneThickness $thickness = DripstoneThickness::TIP;
	protected bool $hanging = true;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->enum($this->thickness);
		$w->bool($this->hanging);
	}

	public function isHanging() : bool{
		return $this->hanging;
	}

	/** @return $this*/
	public function setHanging(bool $hanging) : self{
		$this->hanging = $hanging;
		return $this;
	}

	public function getThickness() : DripstoneThickness{
		return $this->thickness;
	}

	/** @return $this*/
	public function setThickness(DripstoneThickness $thickness) : self{
		$this->thickness = $thickness;
		return $this;
	}



	// todo hanging
	// todo growing thickness

}