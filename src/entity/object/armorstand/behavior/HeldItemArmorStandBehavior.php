<?php

namespace pocketmine\entity\object\armorstand\behavior;

use pocketmine\entity\object\armorstand\ArmorStandEntity;
use pocketmine\event\player\PlayerChangeArmorStandHeldItemEvent;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class HeldItemArmorStandBehavior implements ArmorStandBehavior{

	public function __construct(){
	}

	public function handleEquipment(Player $player, ArmorStandEntity $entity, Vector3 $clickPos) : void{
		$inventory = $player->getInventory();
		$item = $player->getInventory()->getItemInHand();

		$oldItem = $entity->getItemInHand();
		$newItem = $item->pop();

		$ev = new PlayerChangeArmorStandHeldItemEvent($entity, $oldItem, $newItem, $player);
		$ev->call();
		if(!$ev->isCancelled()){
			$inventory->setItemInHand($item);
			foreach($inventory->addItem($oldItem) as $dropped){
				$player->getWorld()->dropItem($player->getEyePos(), $dropped);
			}
			$entity->setItemInHand($newItem);
		}
	}
}