<?php

namespace pocketmine\block;

interface ProximityRestricted{
	public const MAX_DISTANCE = 6;

	/**
	 * Returns the max distance the player can be away
	 */
	public function getMaxDistance() : int;

	/**
	 * Returns the max distance the player can be away
	 */
	public function setMaxDistance(int $maxDistance) : void;
}