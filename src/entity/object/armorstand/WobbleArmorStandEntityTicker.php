<?php

namespace pocketmine\entity\object\armorstand;

final class WobbleArmorStandEntityTicker implements ArmorStandEntityTicker{

	public const DATA_PROPERTY_WOBBLE = 11;
	public const DEFAULT_TICKS = 9;

	private int $ticks;

	public function __construct(ArmorStandEntity $entity, int $ticks = self::DEFAULT_TICKS){
		$this->ticks = $ticks;
		$this->send($entity);
	}

	public function tick(ArmorStandEntity $entity) : bool{
		$this->send($entity);
		return --$this->ticks >= 0;
	}

	private function send(ArmorStandEntity $entity) : void{
		$entity->getNetworkProperties()->setInt(self::DATA_PROPERTY_WOBBLE, $this->ticks);
	}
}