<?php

namespace pocketmine\world\sound;

use pocketmine\item\GoatHornType;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;

class GoatHornSound implements Sound{
	public function __construct(private GoatHornType $goatHornType){
	}

	public function encode(Vector3 $pos) : array{
		return [LevelSoundEventPacket::nonActorSound(match ($this->goatHornType) {
			GoatHornType::PONDER => LevelSoundEvent::HORN_CALL0,
			GoatHornType::SING => LevelSoundEvent::HORN_CALL1,
			GoatHornType::SEEK => LevelSoundEvent::HORN_CALL2,
			GoatHornType::FEEL => LevelSoundEvent::HORN_CALL3,
			GoatHornType::ADMIRE => LevelSoundEvent::HORN_CALL4,
			GoatHornType::CALL => LevelSoundEvent::HORN_CALL5,
			GoatHornType::YEARN => LevelSoundEvent::HORN_CALL6,
			GoatHornType::DREAM => LevelSoundEvent::HORN_CALL7
		}, $pos, false)];
	}
}