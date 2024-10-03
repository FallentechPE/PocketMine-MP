<?php

namespace pocketmine\block\anvil;


use pocketmine\item\Item;

abstract class AnvilAction{
	protected int $xpCost = 0;

	final public function __construct(
		protected Item $base,
		protected Item $material,
		protected ?string $customName
	){ }

	final public function getXpCost() : int{
		return $this->xpCost;
	}

	/**
	 * If only actions marked as free of repair cost is applied, the result item
	 * will not have any repair cost increase.
	 */
	public function isFreeOfRepairCost() : bool {
		return false;
	}

	abstract public function process(Item $resultItem) : void;

	abstract public function canBeApplied() : bool;
}