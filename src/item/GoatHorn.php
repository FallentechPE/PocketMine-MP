<?php

namespace pocketmine\item;

use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\sound\GoatHornSound;

class GoatHorn extends Item implements Releasable{

	private GoatHornType $goatHornType = GoatHornType::PONDER;

	protected function describeState(RuntimeDataDescriber $w) : void{
		$w->enum($this->goatHornType);
	}

	public function getHornType() : GoatHornType{
		return $this->goatHornType;
	}

	/** @return $this */
	public function setHornType(GoatHornType $type) : self{
		$this->goatHornType = $type;
		return $this;
	}

	public function getMaxStackSize() : int{
		return 1;
	}

	public function getCooldownTicks() : int{
		return 140;
	}

	public function canStartUsingItem(Player $player) : bool{
		return true;
	}

	public function onClickAir(Player $player, Vector3 $directionVector, array &$returnedItems) : ItemUseResult{
		$position = $player->getPosition();
		$position->getWorld()->addSound($position, new GoatHornSound($this->goatHornType));

		return ItemUseResult::SUCCESS;
	}

}