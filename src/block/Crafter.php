<?php

namespace pocketmine\block;

use pocketmine\block\utils\Orientation;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

class Crafter extends Opaque{

	protected bool $crafting = false;
	protected Orientation $orientation = Orientation::DOWN_EAST;
	protected bool $triggered = false;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->bool($this->crafting);
		$w->bool($this->triggered);
		$w->enum($this->orientation);
	}

	public function isCrafting() : bool{
		return $this->crafting;
	}

	/** @return $this */
	public function setCrafting(bool $crafting) : self{
		$this->crafting = $crafting;
		return $this;
	}

	public function isTriggered() : bool{
		return $this->triggered;
	}

	/** @return $this */
	public function setTriggered(bool $triggered) : self{
		$this->triggered = $triggered;
		return $this;
	}

	public function getOrientation() : Orientation{
		return $this->orientation;
	}

	/** @return $this */
	public function setOrientation(Orientation $orientation) : self{
		$this->orientation = $orientation;
		return $this;
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		// todo fix orientation
		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}



	// todo redstone
	// todo tile
	// todo inventory

}