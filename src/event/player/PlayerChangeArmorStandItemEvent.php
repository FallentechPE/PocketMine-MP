<?php

namespace pocketmine\event\player;

use pocketmine\entity\object\armorstand\ArmorStandEntity;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\entity\EntityEvent;
use pocketmine\item\Item;
use pocketmine\player\Player;

/**
 * @extends EntityEvent<ArmorStandEntity>
 */
abstract class PlayerChangeArmorStandItemEvent extends EntityEvent implements Cancellable{
	use CancellableTrait;

	protected Item $oldItem;
	protected Item $newItem;
	protected Player $causer;

	public function __construct(ArmorStandEntity $entity, Item $oldItem, Item $newItem, Player $causer){
		$this->entity = $entity;
		$this->oldItem = $oldItem;
		$this->newItem = $newItem;
		$this->causer = $causer;
	}

	final public function getOldItem() : Item{
		return $this->oldItem;
	}

	final public function getNewItem() : Item{
		return $this->newItem;
	}

	final public function getCauser() : Player{
		return $this->causer;
	}
}