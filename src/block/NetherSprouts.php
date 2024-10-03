<?php

namespace pocketmine\block;

use pocketmine\block\utils\StaticSupportTrait;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class NetherSprouts extends Transparent{
	use StaticSupportTrait;


	private function canBeSupportedAt(Block $block) : bool{
		$supportBlock = $block->getSide(Facing::DOWN);
		return (
			$supportBlock->hasTypeTag(BlockTypeTags::DIRT) ||
			$supportBlock->hasTypeTag(BlockTypeTags::MUD) ||
			$supportBlock->getTypeId() === BlockTypeIds::MOSS_BLOCK ||
			$supportBlock->getTypeId() === BlockTypeIds::SOUL_SOIL
			// todo both nylium
		);
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []) : bool{
		// todo bonemeal logic
		return parent::onInteract($item, $face, $clickVector, $player, $returnedItems);
	}
}