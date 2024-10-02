<?php

namespace pocketmine\block\utils;

use pocketmine\inventory\Inventory;
use pocketmine\item\Item;

class HopperTransferHelper{
	/**
	 * Find one item from the source inventory and transfer it to the target inventory.
	 * Check the items from the start to the end of the inventory.
	 * Returns true if an item was transferred, false otherwise.
	 */
	public static function transferOneItem(Inventory $sourceInventory, Inventory $targetInventory) : bool{
		foreach($sourceInventory->getContents() as $item){
			if(self::transferSpecificItem($sourceInventory, $targetInventory, $item)){
				return true;
			}
		}

		return false;
	}

	/**
	 * Transfer the one of the specified item from the source inventory to the target inventory.
	 * Returns true if the item was transferred, false otherwise.
	 */
	public static function transferSpecificItem(Inventory $sourceInventory, Inventory $targetInventory, Item $item) : bool{
		if($item->isNull()){
			return false;
		}

		$singleItem = $item->pop();

		if(!$targetInventory->canAddItem($singleItem)){
			return false;
		}

		$sourceInventory->removeItem($singleItem);
		$targetInventory->addItem($singleItem);

		return true;
	}
}