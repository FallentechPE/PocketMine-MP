<?php

namespace pocketmine\entity\monster\slime;

use pocketmine\entity\ai\control\MoveControl;
use pocketmine\entity\monster\Slime;
use const M_PI;

class SlimeMoveControl extends MoveControl{

	protected float $yaw;

	protected int $jumpDelay = 0;

	protected bool $isAggresive = false;

	public function __construct(protected Slime $slime) {
		$this->yaw = 180 * $slime->getLocation()->getYaw() / M_PI;

		parent::__construct($slime);
	}

	public function setDirection(float $yaw, bool $aggresive) : void{
		$this->yaw = $yaw;
		$this->isAggresive = $aggresive;
	}

	public function setWantedMovement(float $speedModifier) : void{
		$this->speedModifier = $speedModifier;
		$this->operation = MoveControl::OPERATION_MOVE_TO;
	}

	public function tick() : void {
		$location = $this->mob->getLocation();

		$this->mob->setRotation($this->rotateLerp($location->yaw, $this->yaw, 90), $location->pitch);

		if ($this->operation === MoveControl::OPERATION_MOVE_TO) {
			$this->operation = MoveControl::OPERATION_WAIT;
			if ($this->mob->isOnGround()) {
				if (--$this->jumpDelay <= 0) {
					$this->jumpDelay = $this->slime->getJumpDelay();
					if ($this->isAggresive) {
						$this->jumpDelay = (int) ($this->jumpDelay / 3);
					}

					$this->mob->setForwardSpeed($this->speedModifier * $this->mob->getDefaultMovementSpeed());
					$this->slime->getJumpControl()->jump();
				}
			} elseif (!$this->slime->isJumping()) {
				$this->mob->setForwardSpeed(0);
				$this->mob->setSidewaysSpeed(0);
			}
		} elseif (!$this->slime->isJumping()) {
			$this->mob->setForwardSpeed(0);
		}
	}
}
