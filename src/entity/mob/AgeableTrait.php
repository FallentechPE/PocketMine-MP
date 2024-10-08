<?php

namespace pocketmine\entity\mob;

use pocketmine\nbt\tag\CompoundTag;

trait AgeableTrait{
	private int $age = 0;

	protected function saveAgeableNBT(CompoundTag $nbt) : CompoundTag {
		$nbt->setInt("Age", $this->age); // negative is baby, positive is adult
		return $nbt;
	}

	public function getAge() : int{
		return $this->age;
	}

	public function setAge(int $age) : self{
		$this->age = $age;
		return $this;
	}

	protected function doAgeableTick(int $tickDiff = 1) : bool{
		$hasUpdate = false;
		if(!$this->isAlive() || $this->isFlaggedForDespawn()) {
			return false;
		}
		if ($this->age >= self::RESET_AGE_TICKS + self::BABY_TICKS) {
			$this->age = 0;
			$hasUpdate = true;
		}


		$this->age += $tickDiff;
		if($this->age >= self::BABY_TICKS) {
			$this->setIsBaby(false);
			$hasUpdate = true;
		}
		return $hasUpdate;
	}

}