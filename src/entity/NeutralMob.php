<?php

namespace pocketmine\entity;


use pocketmine\event\entity\EntityDamageByEntityEvent;

interface NeutralMob {

	public function getRemainingAngerTime() : int;

	public function setRemainingAngerTime(int $ticks) : void;

	public function startAngerTimer() : void;

	public function stopBeingAngry() : void;

	public function getTargetEntity() : ?Entity;

	public function setTargetEntity(?Entity $target) : void;

	public function isAngryAt(Entity $entity) : bool;

	public function isAngry() : bool;

	public function getLastDamageByEntity() : ?EntityDamageByEntityEvent;

	public function canAttack(Living $target) : bool;
}
