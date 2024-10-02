<?php

namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;

/**
 * Called when a hopper will execute push/pull action on another block.
 * Push action will be called when a hopper wants to push an item into another block.
 * Pull action will be called when a hopper wants to pull an item from another block.
 */
class HopperActionEvent extends BlockEvent implements Cancellable{
	use CancellableTrait;

	public const ACTION_PUSH = 0;
	public const ACTION_PULL = 1;

	/**
	 * @param Block $targetBlock if the action is push, this is the target block. If the action is pull, this is the source block.
	 */
	public function __construct(
		Block $block,
		private Block $targetBlock,
		private int $action,
	){
		parent::__construct($block);
	}

	public function getTargetBlock() : Block{
		return $this->targetBlock;
	}

	public function getAction() : int{
		return $this->action;
	}
}