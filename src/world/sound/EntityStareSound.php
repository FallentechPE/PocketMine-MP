<?php

namespace pocketmine\world\sound;

use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;

class EntityStareSound implements Sound{

	public function __construct(private Entity $entity){ }

	public function encode(Vector3 $pos) : array{
		return [LevelSoundEventPacket::create(
			LevelSoundEvent::STARE,
			$pos,
			-1,
			$this->entity::getNetworkTypeId(),
			false, //TODO: does isBaby have any relevance here?
			false
		)];
	}
}
