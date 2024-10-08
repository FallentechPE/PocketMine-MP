<?php

namespace pocketmine\entity\ai\control;

use pocketmine\entity\Entity;
use pocketmine\entity\Mob;
use pocketmine\math\Vector3;
use pocketmine\utils\Utils;
use function atan2;
use function sqrt;
use const M_PI;

class LookControl implements Control {

	protected Mob $mob;

	protected float $yawMaxRotationAngle;

	protected float $pitchMaxRotationAngle;

	protected bool $hasWanted = false;

	protected Vector3 $wanted;

	public function __construct(Mob $mob) {
		$this->mob = $mob;
	}

	public function setLookAt(Entity|Vector3 $lookAt, ?float $yawMaxRotationAngle = null, ?float $pitchMaxRotationAngle = null) : void {
		$this->wanted = $lookAt instanceof Entity ? $lookAt->getEyePos() : $lookAt;
		$this->yawMaxRotationAngle = $yawMaxRotationAngle ?? $this->mob->getRotSpeed();
		$this->pitchMaxRotationAngle = $pitchMaxRotationAngle ?? $this->mob->getMaxPitchRot();
		$this->hasWanted = true;
	}

	public function tick() : void {
		//TODO: head yaw rotation logic!

		$location = $this->mob->getLocation();
		if ($this->resetPitchOnTick()) {
			$this->mob->setRotation($location->yaw, 0.0);
		}
		if ($this->hasWanted) {
			$this->hasWanted = false;
			$yaw = $this->rotateTowards($location->yaw, $this->getYawD(), $this->yawMaxRotationAngle);
			$pitch = $this->rotateTowards($location->pitch, $this->getPitchD(), $this->pitchMaxRotationAngle);
			$this->mob->setRotation($yaw, $pitch);
		} else {
			$this->mob->setRotation($this->rotateTowards($location->yaw, $location->yaw, 10.0), $location->pitch);
		}
		if (!$this->mob->getNavigation()->isDone()) {
			$this->mob->setRotation(Utils::rotateIfNecessary($location->yaw, $location->yaw, $this->mob->getMaxYawRot()), $location->pitch);
		}
	}

	protected function resetPitchOnTick() : bool {
		return true;
	}

	public function hasWanted() : bool {
		return $this->hasWanted;
	}

	public function getWanted() : ?Vector3 {
		return $this->wanted;
	}

	public function getPitchD() : float {
		$diff = $this->wanted->subtractVector($this->mob->getEyePos());
		return -(atan2($diff->y, sqrt(($diff->x ** 2) + ($diff->z ** 2))) * (180 / M_PI));
	}

	public function getYawD() : float {
		$diff = $this->wanted->subtractVector($this->mob->getEyePos());
		return (atan2($diff->z, $diff->x) * (180 / M_PI)) - 90;
	}

	protected function rotateTowards(float $currentDegrees, float $targetDegrees, float $maxRotation) : float {
		$degreesDifference = Utils::degreesDifference($currentDegrees, $targetDegrees);
		return $currentDegrees + Utils::clamp($degreesDifference, -$maxRotation, $maxRotation);
	}
}
