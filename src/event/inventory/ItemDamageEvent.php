<?php

namespace pocketmine\event\inventory;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\Event;
use pocketmine\item\Durable;

/**
 * Called when an item is damaged
 */
class ItemDamageEvent extends Event implements Cancellable{
	use CancellableTrait;

	public function __construct(
		private Durable $item,
		private int $damage,
		private int $unbreakingDamageReduction = 0
	){
	}

	public function getDamage() : int{
		return $this->damage;
	}

	public function getItem() : Durable{
		return $this->item;
	}

	public function getUnbreakingDamageReduction() : int{
		return $this->unbreakingDamageReduction;
	}

	public function setDamage(int $damage) : void{
		$this->damage = $damage;
	}

	public function setUnbreakingDamageReduction(int $unbreakingDamageReduction) : void{
		$this->unbreakingDamageReduction = $unbreakingDamageReduction;
	}
}