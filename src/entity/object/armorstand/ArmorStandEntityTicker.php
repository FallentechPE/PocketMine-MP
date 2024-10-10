<?php

namespace pocketmine\entity\object\armorstand;

interface ArmorStandEntityTicker{

	public function tick(ArmorStandEntity $entity) : bool;
}