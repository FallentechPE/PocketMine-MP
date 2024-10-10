<?php

namespace pocketmine\event\entity;

use pocketmine\entity\Location;
use pocketmine\entity\object\armorstand\ArmorStandEntity;

/**
 * @extends EntityEvent<ArmorStandEntity>
 */
final class ArmorStandMoveEvent extends EntityEvent{

	private Location $from;
	private Location $to;

	public function __construct(ArmorStandEntity $entity, Location $from, Location $to){
		$this->entity = $entity;
		$this->from = $from;
		$this->to = $to;
	}

	public function getFrom() : Location{
		return $this->from->asLocation();
	}

	public function getTo() : Location{
		return $this->to->asLocation();
	}
}