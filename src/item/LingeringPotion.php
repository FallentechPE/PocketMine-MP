<?php

namespace pocketmine\item;

use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\entity\Location;
use pocketmine\entity\projectile\SplashPotion as SplashPotionEntity;
use pocketmine\entity\projectile\Throwable;
use pocketmine\player\Player;

class LingeringPotion extends ProjectileItem{

	private PotionType $potionType;

	public function __construct(ItemIdentifier $identifier, string $name){
		$this->potionType = PotionType::WATER;
		parent::__construct($identifier, $name);
	}

	protected function describeState(RuntimeDataDescriber $w) : void{
		$w->potionType($this->potionType);
	}

	public function getType() : PotionType{
		return $this->potionType;
	}

	/**
	 * @return $this
	 */
	public function setType(PotionType $type) : self{
		$this->potionType = $type;
		return $this;
	}

	public function getMaxStackSize() : int{
		return 1;
	}

	protected function createEntity(Location $location, Player $thrower) : Throwable{
		return (new SplashPotionEntity($location, $thrower, $this->potionType))->setLinger();
	}

	public function getThrowForce() : float{
		return 0.5;
	}
}