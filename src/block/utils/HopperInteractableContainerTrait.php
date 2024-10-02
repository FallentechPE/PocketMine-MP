<?php

namespace pocketmine\block\utils;

use pocketmine\block\Hopper;
use pocketmine\block\tile\Container;
use pocketmine\block\tile\Hopper as TileHopper;

trait HopperInteractableContainerTrait{
	public function doHopperPush(Hopper $hopperBlock) : bool{
		$currentTile = $this->position->getWorld()->getTile($this->position);
		if(!$currentTile instanceof Container){
			return false;
		}

		$tileHopper = $this->position->getWorld()->getTile($hopperBlock->position);
		if(!$tileHopper instanceof TileHopper){
			return false;
		}

		return HopperTransferHelper::transferOneItem(
			$tileHopper->getInventory(),
			$currentTile->getInventory()
		);
	}

	public function doHopperPull(Hopper $hopperBlock) : bool{
		$currentTile = $this->position->getWorld()->getTile($this->position);
		if(!$currentTile instanceof Container){
			return false;
		}

		$tileHopper = $this->position->getWorld()->getTile($hopperBlock->position);
		if(!$tileHopper instanceof TileHopper){
			return false;
		}

		return HopperTransferHelper::transferOneItem(
			$currentTile->getInventory(),
			$tileHopper->getInventory()
		);
	}

}