<?php

namespace pocketmine\world\sound;

use pocketmine\entity\Ageable;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;

class MobWarningSound implements Sound{

	public function __construct(private Entity $entity){ }

	public function encode(Vector3 $pos) : array{
		$isBaby = $this->entity instanceof Ageable && $this->entity->isBaby();
		return [LevelSoundEventPacket::create(
			$isBaby ? LevelSoundEvent::MOB_WARNING_BABY : LevelSoundEvent::MOB_WARNING,
			$pos,
			-1,
			$this->entity::getNetworkTypeId(),
			$isBaby,
			false
		)];
	}
}
