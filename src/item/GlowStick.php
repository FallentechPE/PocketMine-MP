<?php

namespace pocketmine\item;

use pocketmine\block\utils\DyeColor;
use pocketmine\data\runtime\RuntimeDataDescriber;

class GlowStick extends Durable{
	private DyeColor $color = DyeColor::BLACK;

	protected function describeState(RuntimeDataDescriber $w) : void{
		$w->enum($this->color);
	}

	public function getColor() : DyeColor{
		return $this->color;
	}

	/**
	 * @return $this
	 */
	public function setColor(DyeColor $color) : self{
		$this->color = $color;
		return $this;
	}

	public function getMaxDurability() : int{
		return 100;
	}
}