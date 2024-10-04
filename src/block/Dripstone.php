<?php

namespace pocketmine\block;

use pocketmine\item\Item;

class Dripstone extends Opaque{

	// todo ticking

	public function getDropsForIncompatibleTool(Item $item) : array{
		return [];
	}

}