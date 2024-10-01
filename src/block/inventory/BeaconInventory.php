<?php

namespace pocketmine\block\inventory;

use pocketmine\inventory\SimpleInventory;
use pocketmine\inventory\TemporaryInventory;
use pocketmine\item\Item;
use pocketmine\world\Position;

class BeaconInventory extends SimpleInventory implements BlockInventory, TemporaryInventory{
	use BlockInventoryTrait;

	public const SLOT_INPUT = 0;

	public function __construct(Position $holder){
		$this->holder = $holder;
		parent::__construct(1);
	}

	public function getInput() : Item{
		return $this->getItem(self::SLOT_INPUT);
	}

	public function setInput(Item $item) : void{
		$this->setItem(self::SLOT_INPUT, $item);
	}

}