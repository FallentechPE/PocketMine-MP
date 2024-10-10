<?php

namespace pocketmine\entity\object\armorstand;

interface ArmorStandPose{

	public function getName() : string;

	public function getNetworkId() : int;
}