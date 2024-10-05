<?php

namespace pocketmine\block;


use pocketmine\block\utils\FacesOppositePlacingPlayerTrait;
use pocketmine\block\utils\VaultState;
use pocketmine\data\runtime\RuntimeDataDescriber;

class Vault extends Transparent{
	use FacesOppositePlacingPlayerTrait;

	protected bool $ominous = false;
	protected VaultState $state = VaultState::INACTIVE;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->horizontalFacing($this->facing);
		$w->bool($this->ominous);
		$w->enum($this->state);
	}

	public function isOminous() : bool{
		return $this->ominous;
	}

	/** @return $this */
	public function setOminous(bool $ominous) : self{
		$this->ominous = $ominous;
		return $this;
	}

	public function getState() : VaultState{
		return $this->state;
	}

	/** @return $this */
	public function setState(VaultState $state) : self {
		$this->state = $state;
		return $this;
	}

	public function getLightLevel() : int{
		return match ($this->state) {
			VaultState::ACTIVE, VaultState::EJECTING => 12,
			default => 6
		};
		// todo is unlocking 6?
	}

}