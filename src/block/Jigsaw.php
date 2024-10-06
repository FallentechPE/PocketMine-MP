<?php

namespace pocketmine\block;

use pocketmine\block\utils\AnyFacingTrait;
use pocketmine\block\utils\PillarRotationTrait;
use pocketmine\data\runtime\RuntimeDataDescriber;

class Jigsaw extends Opaque{
	use AnyFacingTrait {
		describeBlockOnlyState as describeFacing;
	}

	public const MIN_ROTATION = 0;
	public const MAX_ROTATION = 3;

	protected int $rotation = 0;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$this->describeFacing($w);
		$w->boundedIntAuto(self::MIN_ROTATION, self::MAX_ROTATION, $this->rotation);
	}

	public function getRotation() : int{
		return $this->rotation;
	}

	/** @return $this */
	public function setRotation(int $rotation) : self {
		$this->rotation = $rotation;
		return $this;
	}

}