<?php

namespace pocketmine\entity\object\armorstand\behavior;

use pocketmine\entity\object\armorstand\ArmorStandEntity;
use pocketmine\event\player\PlayerChangeArmorStandArmorEvent;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class ArmorPieceArmorStandBehavior implements ArmorStandBehavior{

	public function __construct(
		private int $slot
	){}

	public function handleEquipment(Player $player, ArmorStandEntity $entity, Vector3 $clickPos) : void{
		$inventory = $player->getInventory();
		$item = $player->getInventory()->getItemInHand();

		$entityInventory = $entity->getArmorInventory();
		$oldItem = $entityInventory->getItem($this->slot);
		$newItem = $item->pop();

		$ev = new PlayerChangeArmorStandArmorEvent($entity, $this->slot, $oldItem, $newItem, $player);
		$ev->call();
		if(!$ev->isCancelled()){
			$inventory->setItemInHand($item);
			foreach($inventory->addItem($oldItem) as $dropped){
				$player->getWorld()->dropItem($player->getEyePos(), $dropped);
			}
			$entityInventory->setItem($this->slot, $newItem);
		}
	}
}