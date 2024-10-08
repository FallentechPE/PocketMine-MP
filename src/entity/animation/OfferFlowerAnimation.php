<?php

namespace pocketmine\entity\animation;

use pocketmine\entity\golem\IronGolem;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\network\mcpe\protocol\types\ActorEvent;

final class OfferFlowerAnimation implements Animation{

	public function __construct(private IronGolem $golem, private int $durationTicks){}

	public function encode() : array{
		return [
			ActorEventPacket::create($this->golem->getId(), ActorEvent::IRON_GOLEM_OFFER_FLOWER, $this->durationTicks)
		];
	}
}
