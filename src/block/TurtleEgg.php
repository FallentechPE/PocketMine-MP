<?php

namespace pocketmine\block;

use pocketmine\block\utils\EggTrait;
use pocketmine\block\utils\TurtleEggCount;
use pocketmine\block\utils\CrackedState;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

class TurtleEgg extends Flowable{
	use EggTrait {
		describeBlockOnlyState as describeCrackedState;
	}

	protected TurtleEggCount $eggCount = TurtleEggCount::ONE_EGG;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->enum($this->eggCount);
		$this->describeCrackedState($w);
	}

	public function getEggCount() : TurtleEggCount{ return $this->eggCount; }

	/** @return $this */
	public function setEggCount(TurtleEggCount $eggCount) : self{
		$this->eggCount = $eggCount;
		return $this;
	}

	public function canBePlacedAt(Block $blockReplace, Vector3 $clickVector, int $face, bool $isClickedBlock) : bool{
		//TODO: proper placement logic (needs a supporting face below)
		return ($blockReplace instanceof TurtleEgg && $blockReplace->eggCount < TurtleEggCount::FOUR_EGG) || parent::canBePlacedAt($blockReplace, $clickVector, $face, $isClickedBlock);
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		if($blockReplace instanceof TurtleEgg && $blockReplace->eggCount < TurtleEggCount::FOUR_EGG){
			$this->eggCount = $blockReplace->eggCount + 1;
		}

		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}


}