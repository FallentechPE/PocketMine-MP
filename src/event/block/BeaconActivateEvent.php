<?php

namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\entity\effect\Effect;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;

class BeaconActivateEvent extends BlockEvent implements Cancellable{
	use CancellableTrait;

	protected Effect $primaryEffect;
	protected ?Effect $secondaryEffect;

	public function __construct(Block $block, Effect $primaryEffect, ?Effect $secondaryEffect = null){
		parent::__construct($block);
		$this->primaryEffect = $primaryEffect;
		$this->secondaryEffect = $secondaryEffect;
	}

	public function getPrimaryEffect() : Effect{
		return $this->primaryEffect;
	}

	public function getSecondaryEffect() : ?Effect{
		return $this->secondaryEffect;
	}

	public function setPrimaryEffect(Effect $primary) : void{
		$this->primaryEffect = $primary;
	}

	public function setSecondaryEffect(?Effect $secondary) : void{
		$this->secondaryEffect = $secondary;
	}

}