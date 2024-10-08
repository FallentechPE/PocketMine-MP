<?php

namespace pocketmine\entity;

use pocketmine\event\entity\EntityDamageByEntityEvent;

trait NeutralMobTrait {

	abstract public function getTargetEntity() : ?Entity;

	abstract public function setTargetEntity(?Entity $target) : void;

	abstract public function setLastDamageByEntity(?EntityDamageByEntityEvent $type) : void;

	abstract public function getRemainingAngerTime() : int;

	abstract public function setRemainingAngerTime(int $ticks) : void;

	abstract public function startAngerTimer() : void;

	public function updateAnger(bool $value) : void{
		$target = $this->getTargetEntity();
		if ($target !== null && !$target->isAlive()) {
			$this->stopBeingAngry();
		} else {
			if (!$this->isAngry() && $target !== null) {
				$this->startAngerTimer();
			}

			if ($this->isAngry() && ($target === null || $target instanceof Human || !$value)) {
				$this->setRemainingAngerTime($this->getRemainingAngerTime() - 1);
				if ($this->getRemainingAngerTime() === 0) {
					$this->stopBeingAngry();
				}
			}
		}
	}

	public function isAngryAt(Entity $entity) : bool{
		if (!$entity instanceof Living || !$this->canAttack($entity)) {
			return false;
		}

		return $this->getTargetEntityId() === $entity->getId();
	}

	public function isAngry() : bool{
		return $this->getRemainingAngerTime() > 0;
	}

	public function stopBeingAngry() : void{
		$this->setLastDamageByEntity(null);
		$this->setTargetEntity(null);
		$this->setRemainingAngerTime(0);
	}
}
