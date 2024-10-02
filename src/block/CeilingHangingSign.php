<?php

namespace pocketmine\block;

use pocketmine\block\utils\SignLikeRotationTrait;
use pocketmine\block\utils\SupportType;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;
use function var_dump;

final class CeilingHangingSign extends BaseSign{
	use SignLikeRotationTrait {
		describeBlockOnlyState as describeSignRotation;
	}

	private bool $centerAttached = false;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		parent::describeBlockOnlyState($w);
		$this->describeSignRotation($w);
		$w->bool($this->centerAttached);
	}

	protected function getSupportingFace() : int{
		return Facing::UP;
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if($face !== Facing::DOWN){
			return false;
		}

		$supportType = $blockReplace->getAdjacentSupportType(Facing::UP);
		if($supportType->equals(SupportType::CENTER)){
			$this->centerAttached = true;
		}
		if($player !== null){
			$this->rotation = self::getRotationFromYaw($player->getLocation()->getYaw());
			var_dump($this->rotation);
		}
		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	/**
	 * Returns whether the sign is attached to a single point on the block above.
	 * If false, the sign has two chains attached to different points on the block above.
	 */
	public function isCenterAttached() : bool{ return $this->centerAttached; }

	public function setCenterAttached(bool $centerAttached) : self{
		$this->centerAttached = $centerAttached;
		return $this;
	}

	//TODO: these may have a solid collision box
}