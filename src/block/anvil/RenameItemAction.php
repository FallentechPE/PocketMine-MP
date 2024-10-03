<?php

namespace pocketmine\block\anvil;

use pocketmine\item\Item;
use function strlen;

final class RenameItemAction extends AnvilAction{
	private const COST = 1;

	public function canBeApplied() : bool{
		return true;
	}

	public function process(Item $resultItem) : void{
		if($this->customName === null || strlen($this->customName) === 0){
			if($this->base->hasCustomName()){
				$this->xpCost += self::COST;
				$resultItem->clearCustomName();
			}
		}else{
			if($this->base->getCustomName() !== $this->customName){
				$this->xpCost += self::COST;
				$resultItem->setCustomName($this->customName);
			}
		}
	}
}