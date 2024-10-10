<?php

namespace pocketmine\entity\object\armorstand\behavior;

use pocketmine\entity\object\armorstand\ArmorStandEntity;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

interface ArmorStandBehavior{

	public function handleEquipment(Player $player, ArmorStandEntity $entity, Vector3 $clickPos) : void;
}