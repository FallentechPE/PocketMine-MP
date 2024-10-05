<?php

namespace pocketmine\block;

use pocketmine\block\utils\StructureType;
use pocketmine\data\runtime\RuntimeDataDescriber;

class StructureBlock extends Opaque{

	protected StructureType $structureType = StructureType::DATA;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->enum($this->structureType);
	}

	public function getStructureType() : StructureType{
		return $this->structureType;
	}

	/** @return $this */
	public function setStructureType(StructureType $structureType) : self {
		$this->structureType = $structureType;
		return $this;
	}

}