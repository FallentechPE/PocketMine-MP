<?php

namespace pocketmine\entity\object\armorstand;

final class SimpleArmorStandPose implements ArmorStandPose{

	public function __construct(
		private string $name,
		private int $networkId
	){}

	public function getName() : string{
		return $this->name;
	}

	public function getNetworkId() : int{
		return $this->networkId;
	}
}