<?php

namespace pocketmine\item;

class PotterySherd extends Item{
	private PotterySherdType $potterySherdType;

	public function __construct(ItemIdentifier $identifier, string $name, PotterySherdType $potterySherdType){
		parent::__construct($identifier, $name);
		$this->potterySherdType = $potterySherdType;
	}

	public function getType() : PotterySherdType{
		return $this->potterySherdType;
	}

}