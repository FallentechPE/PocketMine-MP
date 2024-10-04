<?php

namespace pocketmine\block;

use pocketmine\block\utils\HorizontalFacingTrait;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

class BeeNest extends Opaque{
	use HorizontalFacingTrait;

	public const MIN_HONEY = 0;
	public const MAX_HONEY = 5;

	protected int $honey = self::MIN_HONEY;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->horizontalFacing($this->facing);
		$w->boundedIntAuto(self::MIN_HONEY, self::MAX_HONEY, $this->honey);
	}

	public function getHoney() : int{ return $this->honey; }

	/** @return $this */
	public function setHoney(int $honey) : self{
		if($honey < self::MIN_HONEY || $honey > self::MAX_HONEY){
			throw new \InvalidArgumentException("Count must be in range " . self::MIN_HONEY . " ... " . self::MAX_HONEY);
		}
		$this->honey = $honey;
		return $this;
	}

	public function getFlameEncouragement() : int{
		return 30;
	}

	public function getFlammability() : int{
		return 20;
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if($player !== null){
			$this->facing = Facing::opposite($player->getHorizontalFacing());
		}
		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}
}