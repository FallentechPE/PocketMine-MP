<?php

namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\item\Item;

/**
 * Called when a block is destroyed because its support has been destroyed.
 */
class BlockSupportBreakEvent extends BlockEvent{
	/** @var Item[] */
	protected array $blockDrops = [];

	/**
	 * @param Item[] $drops
	 */
	public function __construct(
		Block $block,
		array $drops = [],
		protected int $xpDrops = 0
	){
		parent::__construct($block);
		$this->setDrops($drops);
	}

	/**
	 * @return Item[]
	 */
	public function getDrops() : array{
		return $this->blockDrops;
	}

	/**
	 * @param Item[] $drops
	 */
	public function setDrops(array $drops) : void{
		$this->setDropsVariadic(...$drops);
	}

	/**
	 * Variadic hack for easy array member type enforcement.
	 */
	public function setDropsVariadic(Item ...$drops) : void{
		$this->blockDrops = $drops;
	}

	/**
	 * Returns how much XP will be dropped by breaking this block.
	 */
	public function getXpDropAmount() : int{
		return $this->xpDrops;
	}

	/**
	 * Sets how much XP will be dropped by breaking this block.
	 */
	public function setXpDropAmount(int $amount) : void{
		if($amount < 0){
			throw new \InvalidArgumentException("Amount must be at least zero");
		}
		$this->xpDrops = $amount;
	}
}