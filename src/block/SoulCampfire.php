<?php

namespace pocketmine\block;

use pocketmine\crafting\FurnaceType;
use pocketmine\item\Item;

class SoulCampfire extends Campfire{

	public function getLightLevel() : int{
		return $this->lit ? 10 : 0;
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		return [
			VanillaBlocks::SOUL_SOIL()->asItem()
		];
	}

	protected function getEntityCollisionDamage() : int{
		return 2;
	}

	protected function getFurnaceType() : FurnaceType{
		return FurnaceType::SOUL_CAMPFIRE;
	}

}