<?php

namespace pocketmine\entity\animation;

use pocketmine\entity\animal\Animal;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\network\mcpe\protocol\types\ActorEvent;

final class EatBlockAnimation implements Animation{

	public function __construct(private Animal $animal){}

	public function encode() : array{
		return [
			ActorEventPacket::create($this->animal->getId(), ActorEvent::EAT_GRASS_ANIMATION, 0)
		];
	}
}
