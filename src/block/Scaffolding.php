<?php

namespace pocketmine\block;

use pocketmine\block\utils\Fallable;
use pocketmine\block\utils\FallableTrait;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\entity\Entity;
use pocketmine\math\AxisAlignedBB;

class Scaffolding extends Transparent implements Fallable{ // todo add stability logic
	use FallableTrait;

	public const MAX_STABILITY = 7;

	protected bool $stable = false;
	protected int $stability = 0;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->bool($this->stable);
		$w->boundedIntAuto(0, self::MAX_STABILITY, $this->stability);
	}

	public function isStable() : bool{
		return $this->stable;
	}

	/** @return $this */
	public function setStable(bool $stable) : self{
		$this->stable = $stable;
		return $this;
	}

	public function getStability() : int{
		return $this->stability;
	}

	/** @return $this */
	public function setStability(int $stability) : self{
		$this->stability = $stability;
		return $this;
	}

	public function getFlameEncouragement() : int{
		return 60;
	}

	public function getFlammability() : int{
		return 60;
	}

	public function canClimb() : bool{
		return true;
	}

	public function canBeFlowedInto() : bool{
		return false;
	}

	protected function recalculateCollisionBoxes() : array{
		return [AxisAlignedBB::one()->contract(0, (2 / 16), 0)];
	}

	public function onEntityInside(Entity $entity) : bool{
		$entity->resetFallDistance();
		return true;
	}

	public function hasEntityCollision() : bool{
		return true;
	}
}