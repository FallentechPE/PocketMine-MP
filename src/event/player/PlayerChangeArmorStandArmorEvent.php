<?php

namespace pocketmine\event\player;

use pocketmine\entity\object\armorstand\ArmorStandEntity;
use pocketmine\item\Item;
use pocketmine\player\Player;

final class PlayerChangeArmorStandArmorEvent extends PlayerChangeArmorStandItemEvent{

	protected int $slot;

	public function __construct(ArmorStandEntity $entity, int $slot, Item $oldItem, Item $newItem, Player $causer){
		parent::__construct($entity, $oldItem, $newItem, $causer);
		$this->slot = $slot;
	}

	public function getSlot() : int{
		return $this->slot;
	}
}