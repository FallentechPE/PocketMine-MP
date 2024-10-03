<?php

namespace pocketmine\block\utils;

use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\math\Facing;

/**
 * Used by blocks that can have multiple target faces in the area of one solid block, such as covering three sides of a corner.
 */
trait MultiFacingTrait{

	/** @var int[] */
	protected array $faces = [];

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->facingFlags($this->faces);
	}

	/** @return int[] */
	public function getFaces() : array{ return $this->faces; }

	public function hasFace(int $face) : bool{
		return isset($this->faces[$face]);
	}

	/**
	 * @param int[] $faces
	 * @return $this
	 */
	public function setFaces(array $faces) : self{
		$uniqueFaces = [];
		foreach($faces as $face){
			Facing::validate($face);
			$uniqueFaces[$face] = $face;
		}
		$this->faces = $uniqueFaces;
		return $this;
	}

	/** @return $this */
	public function setFace(int $face, bool $value) : self{
		Facing::validate($face);
		if($value){
			$this->faces[$face] = $face;
		}else{
			unset($this->faces[$face]);
		}
		return $this;
	}
}