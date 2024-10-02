<?php

namespace pocketmine\block;

/**
 * This interface is used to mark blocks that can be interacted with by hoppers.
 * This is used to prevent hoppers from trying to interact with blocks that don't support it.
 * If you want to make a block interactable by hoppers, implement this interface.
 */
interface HopperInteractable{
	/**
	 * Returns true/false if a hopper was successfully able to
	 * push an item into this block (e.g. an item was successfully added to the block inventory)
	 */
	public function doHopperPush(Hopper $hopperBlock) : bool;

	/**
	 * Returns true/false if a hopper was successfully able to
	 * pull an item from this block (e.g. an item was successfully removed from the block inventory)
	 */
	public function doHopperPull(Hopper $hopperBlock) : bool;
}