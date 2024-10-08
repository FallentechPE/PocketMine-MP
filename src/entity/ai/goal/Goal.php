<?php

namespace pocketmine\entity\ai\goal;

use function ceil;

abstract class Goal {

	public const FLAG_MOVE = 0;
	public const FLAG_LOOK = 1;
	public const FLAG_JUMP = 2;
	public const FLAG_TARGET = 3;

	/** @var int[] */
	protected array $flags = [];

	abstract public function canUse() : bool;

	public function canContinueToUse() : bool{
		return $this->canUse();
	}

	public function isInterruptable() : bool{
		return true;
	}

	public function start() : void{
	}

	public function stop() : void{
	}

	public function requiresUpdateEveryTick() : bool{
		return false;
	}

	public function tick() : void{
	}

	/**
	 * @return int[]
	 */
	public function getFlags() : array{
		return $this->flags;
	}

	public function setFlags(int ...$flags) : void{
		$this->flags = $flags;
	}

	public function adjustedTickDelay(int $ticks) : int{
		return $this->requiresUpdateEveryTick() ? $ticks : $this->reducedTickDelay($ticks);
	}

	public function reducedTickDelay(int $ticks) : int{
		return (int) ceil($ticks / 2);
	}
}
