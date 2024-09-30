<?php

namespace pocketmine\block;

use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class MossBlock extends Opaque{

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []) : bool{
		// todo add bonemeal logic
		return parent::onInteract($item, $face, $clickVector, $player, $returnedItems);
	}

}