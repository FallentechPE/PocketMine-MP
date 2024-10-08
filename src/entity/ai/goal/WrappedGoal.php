<?php

namespace pocketmine\entity\ai\goal;

class WrappedGoal extends Goal {

	private Goal $goal;

	private int $priority;

	private bool $isRunning = false;

	public function __construct(int $priority, Goal $goal) {
		$this->priority = $priority;
		$this->goal = $goal;
	}

	public function canBeReplacedBy(WrappedGoal $goal) : bool{
		return $this->isInterruptable() && $goal->getPriority() < $this->getPriority();
	}

	public function canUse() : bool{
		return $this->goal->canUse();
	}

	public function canContinueToUse() : bool{
		return $this->goal->canContinueToUse();
	}

	public function isInterruptable() : bool{
		return $this->goal->isInterruptable();
	}

	public function start() : void{
		if (!$this->isRunning) {
			$this->isRunning = true;
			$this->goal->start();
		}
	}

	public function stop() : void{
		if ($this->isRunning) {
			$this->isRunning = false;
			$this->goal->stop();
		}
	}

	public function tick() : void{
		$this->goal->tick();
	}

	/**
	 * @return int[]
	 */
	public function getFlags() : array{
		return $this->goal->getFlags();
	}

	public function setFlags(int ...$flags) : void{
		$this->goal->setFlags(...$flags);
	}

	public function requiresUpdateEveryTick() : bool{
		return $this->goal->requiresUpdateEveryTick();
	}

	public function adjustedTickDelay(int $ticks) : int{
		return $this->goal->adjustedTickDelay($ticks);
	}

	public function isRunning() : bool{
		return $this->isRunning;
	}

	public function getPriority() : int{
		return $this->priority;
	}

	public function getGoal() : Goal{
		return $this->goal;
	}

	public function equals(?Goal $goal) : bool{
		if ($goal === $this) {
			return true;
		}
		return $goal instanceof WrappedGoal && $this->goal::class === $goal->goal::class;
	}
}
