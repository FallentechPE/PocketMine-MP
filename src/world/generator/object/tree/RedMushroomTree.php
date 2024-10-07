<?php

namespace pocketmine\world\generator\object\tree;

use pocketmine\block\RedMushroomBlock;
use pocketmine\block\VanillaBlocks;

class RedMushroomTree extends BrownMushroomTree{

	protected function getType() : RedMushroomBlock{
		return VanillaBlocks::RED_MUSHROOM_BLOCK();
	}
}