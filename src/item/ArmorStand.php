<?php

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\entity\Location;
use pocketmine\entity\object\armorstand\ArmorStandEntity;
use pocketmine\event\player\PlayerPlaceArmorStandEvent;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class ArmorStand extends Item{

	public function onInteractBlock(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, array &$returnedItems) : ItemUseResult{
		if(!$blockClicked->isSolid()){
			return parent::onInteractBlock($player, $blockReplace, $blockClicked, $face, $clickVector, $returnedItems);
		}

		$position = $blockClicked->getPosition();
		$world = $position->getWorld();
		$spawnPosition = $position->addVector(Vector3::zero()->getSide($face))->add(0.5, 0.0, 0.5);
		foreach($world->getNearbyEntities((new AxisAlignedBB(-0.5, 0.0, -0.5, 0.5, 1.0, 0.5))->offset($spawnPosition->x, $spawnPosition->y, $spawnPosition->z)) as $entity){
			if($entity instanceof ArmorStandEntity){
				return ItemUseResult::NONE;
			}
		}

		$yaw = fmod($player->getLocation()->getYaw() + 180.0, 360.0); // inverted player yaw
		$yaw = round($yaw / 45.0) * 45.0; // round to nearest 45.0

		$ev = new PlayerPlaceArmorStandEvent($player, Location::fromObject($spawnPosition, $world, $yaw));
		$ev->call();
		if($ev->isCancelled()){
			return ItemUseResult::NONE;
		}

		$entity = new ArmorStandEntity($ev->getLocation());
		$entity->spawnToAll();

		$this->pop();
		return ItemUseResult::SUCCESS;

	}

}