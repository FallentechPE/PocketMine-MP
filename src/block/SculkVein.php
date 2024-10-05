<?php

namespace pocketmine\block;

use pocketmine\block\utils\MultiFacingTrait;
use pocketmine\block\utils\MultiSupportTrait;
use pocketmine\block\utils\SupportType;
use pocketmine\math\AxisAlignedBB;

class SculkVein extends Transparent{
	use MultiFacingTrait;
	use MultiSupportTrait;

	public function isSolid() : bool{
		return false;
	}

	/**
	 * @return AxisAlignedBB[]
	 */
	protected function recalculateCollisionBoxes() : array{
		return [];
	}

	public function getSupportType(int $facing) : SupportType{
		return SupportType::NONE;
	}

	public function canBeReplaced() : bool{
		return true;
	}

	/**
	 * @return int[]
	 */
	protected function getInitialPlaceFaces(Block $blockReplace) : array{
		return $blockReplace instanceof SculkVein ? $blockReplace->faces : [];
	}


}