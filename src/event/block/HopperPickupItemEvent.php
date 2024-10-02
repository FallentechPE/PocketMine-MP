<?php

namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\entity\object\ItemEntity;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;

/**
 * Called when a hopper pick up an item from an entity.
 */
class HopperPickupItemEvent extends BlockEvent implements Cancellable{
	use CancellableTrait;

	public function __construct(
		private ItemEntity $entity,
		Block $block
	){
		parent::__construct($block);
	}

	public function getEntity() : ItemEntity{
		return $this->entity;
	}
}