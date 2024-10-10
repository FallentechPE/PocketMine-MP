<?php

namespace pocketmine\event\player;

use pocketmine\entity\object\armorstand\ArmorStandEntity;
use pocketmine\entity\object\armorstand\ArmorStandPose;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\entity\EntityEvent;
use pocketmine\player\Player;

/**
 * @extends EntityEvent<ArmorStandEntity>
 */
final class PlayerChangeArmorStandPoseEvent extends EntityEvent implements Cancellable{
	use CancellableTrait;

	protected ArmorStandPose $oldPose;
	protected ArmorStandPose $newPose;
	protected Player $causer;

	public function __construct(ArmorStandEntity $entity, ArmorStandPose $oldPose, ArmorStandPose $newPose, Player $causer){
		$this->entity = $entity;
		$this->oldPose = $oldPose;
		$this->newPose = $newPose;
		$this->causer = $causer;
	}

	public function getOldPose() : ArmorStandPose{
		return $this->oldPose;
	}

	public function getNewPose() : ArmorStandPose{
		return $this->newPose;
	}

	public function setNewPose(ArmorStandPose $newPose) : void{
		$this->newPose = $newPose;
	}

	public function getCauser() : Player{
		return $this->causer;
	}
}