<?php

namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\item\Item;

class ComposterFillEvent extends BlockEvent implements Cancellable{
	use CancellableTrait;

	public function __construct(
		Block $block,
		protected Item $item,
		protected int $fillLevel,
		protected bool $success
	){
		parent::__construct($block);
	}

	public function getItem() : Item{
		return $this->item;
	}

	public function getFillLevel() : int{
		return $this->fillLevel;
	}

	public function isSuccess() : bool{
		return $this->success;
	}

	public function setSuccess(bool $success) : void{

		$this->success = $success;
	}
}