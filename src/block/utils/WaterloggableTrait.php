<?php

namespace pocketmine\block\utils;

use pocketmine\block\Block;
use pocketmine\block\Water;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

trait WaterloggableTrait{
	protected ?Water $waterState = null;

	public function getWaterState() : ?Water{
		return $this->waterState;
	}

	public function setWaterState(?Water $state) : self{
		$this->waterState = $state;

		return $this;
	}

	public function readStateFromWorld() : self{
		parent::readStateFromWorld();
		$this->waterState?->readStateFromWorld();

		return $this;
	}

	public function onNearbyBlockChange() : void{
		$this->waterState?->onNearbyBlockChange();
	}

	public function onScheduledUpdate() : void{
		$this->waterState?->onScheduledUpdate();
	}

	public function onBreak(Item $item, ?Player $player = null, array &$returnedItems = []) : bool{
		$ret = parent::onBreak($item, $player, $returnedItems);
		if($this->waterState !== null){
			$this->position->getWorld()->setBlock($this->position, $this->waterState);
		}

		return $ret;
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null) : bool{
		$ret = parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
		if($blockReplace instanceof Water && $blockReplace->isSource()){
			$this->waterState = $blockReplace;
		}

		return $ret;
	}
}