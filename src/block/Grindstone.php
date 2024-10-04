<?php

namespace pocketmine\block;

use pocketmine\block\utils\BellAttachmentType;
use pocketmine\block\utils\HorizontalFacingTrait;
use pocketmine\data\runtime\RuntimeDataDescriber;

class Grindstone extends Transparent implements FacingInterface{
	use HorizontalFacingTrait;

	private BellAttachmentType $attachmentType = BellAttachmentType::FLOOR; // todo rename bell attachment

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->enum($this->attachmentType);
		$w->horizontalFacing($this->facing);
	}

	public function getAttachmentType() : BellAttachmentType{ return $this->attachmentType; }

	/** @return $this */
	public function setAttachmentType(BellAttachmentType $attachmentType) : self{
		$this->attachmentType = $attachmentType;
		return $this;
	}

}