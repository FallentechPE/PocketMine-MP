<?php

namespace pocketmine\entity\object\armorstand\behavior;


use InvalidArgumentException;
use pocketmine\entity\object\armorstand\ArmorStandEntity;
use pocketmine\entity\object\armorstand\ArmorStandOffsetSlotFinder;
use pocketmine\event\player\PlayerChangeArmorStandArmorEvent;
use pocketmine\event\player\PlayerChangeArmorStandHeldItemEvent;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

final class NullItemArmorStandBehavior implements ArmorStandBehavior{

	public function __construct(){
	}

	public function handleEquipment(Player $player, ArmorStandEntity $entity, Vector3 $clickPos) : void{
		$inventory = $player->getInventory();
		$item = $player->getInventory()->getItemInHand();
		if(!$item->isNull()){
			throw new InvalidArgumentException(self::class . " does not accept item $item");
		}

		$offset = $clickPos->subtractVector($entity->getPosition());
		$newItem = VanillaItems::AIR(); // or, $item
		if(ArmorStandOffsetSlotFinder::isRightArm($offset)){
			$oldItem = $entity->getItemInHand();
			if(!$oldItem->isNull()){
				$ev = new PlayerChangeArmorStandHeldItemEvent($entity, $oldItem, $newItem, $player);
				$ev->call();
				if(!$ev->isCancelled()){
					$inventory->setItemInHand($oldItem);
					$entity->setItemInHand($newItem);
				}
			}
		}else{
			$armorSlot = ArmorStandOffsetSlotFinder::findArmorInventorySlot($offset/* TODO: divide offset by scale? */) ?? ArmorInventory::SLOT_HEAD;
			$armorInventory = $entity->getArmorInventory();
			$oldItem = $armorInventory->getItem($armorSlot);
			if(!$oldItem->isNull()){
				$ev = new PlayerChangeArmorStandArmorEvent($entity, $armorSlot, $oldItem, $newItem, $player);
				$ev->call();
				if(!$ev->isCancelled()){
					$inventory->setItemInHand($oldItem);
					$armorInventory->setItem($armorSlot, $newItem);
				}
			}
		}
	}
}