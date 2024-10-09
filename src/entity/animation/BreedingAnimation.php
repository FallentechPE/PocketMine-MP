<?php

namespace pocketmine\entity\animation;

use pocketmine\entity\animal\Animal;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\network\mcpe\protocol\types\ActorEvent;

final class BreedingAnimation implements Animation{

	public function __construct(private Animal $animal){}

	public function encode() : array{
		return [
			ActorEventPacket::create($this->animal->getId(), ActorEvent::LOVE_PARTICLES, 0)
		];
	}
}