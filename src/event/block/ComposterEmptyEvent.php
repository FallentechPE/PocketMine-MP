<?php

namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\item\Item;

class ComposterEmptyEvent extends BlockEvent implements Cancellable{
	use CancellableTrait;

	/** @var Item[] */
	protected array $dropItems;

	/**
	 * @param Item[] $drops
	 */
	public function __construct(
		Block $block,
		array $drops
	){
		parent::__construct($block);
		$this->setDrops($drops);
	}

	/**
	 * @return Item[]
	 */
	public function getDrops() : array{
		return $this->dropItems;
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
		$this->dropItems = $drops;
	}
}