<?php

namespace pocketmine\block;

interface FacingInterface {

	/**
	 * Get the current facing direction of the block
	 */
	public function getFacing() : int;

	/**
	 * Set the current facing direction of the block
	 */
	public function setFacing(int $facing) : Block;

}