<?php

namespace pocketmine\block;

use pocketmine\block\utils\HorizontalFacingTrait;
use pocketmine\item\Item;
use pocketmine\math\Axis;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

final class WallHangingSign extends BaseSign{
	use HorizontalFacingTrait;

	protected function getSupportingFace() : int{
		return Facing::rotateY($this->facing, true);
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if(Facing::axis($face) === Axis::Y){
			return false;
		}
		$this->facing = Facing::rotateY($face, true);
		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	//TODO: these have a solid collision box
}