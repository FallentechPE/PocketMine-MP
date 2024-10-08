<?php

namespace pocketmine\entity\ai\goal;

use Closure;
use InvalidArgumentException;
use pocketmine\timings\Timings;
use function array_filter;

class GoalSelector {

	/** @var array<int, WrappedGoal> flag => WrappedGoal */
	protected array $lockedFlags = [];

	/** @var WrappedGoal[] */
	protected array $availableGoals = [];

	/** @var array<int, int> flag => flag */
	protected array $disabledFlags = [];

	protected int $newGoalRate = 3;

	public function addGoal(int $priority, Goal $goal) : void{
		$this->availableGoals[] = new WrappedGoal($priority, $goal);
	}

	/**
	 * @phpstan-param Closure(Goal $goal) : bool $predicate
	 */
	public function removeAllGoals(Closure $predicate) : void {
		$this->availableGoals = array_filter($this->availableGoals, function (WrappedGoal $wrappedGoal) use ($predicate) {
			return !$predicate($wrappedGoal->getGoal());
		});
	}

	public function removeGoal(Goal $goal) : void {
		foreach ($this->availableGoals as $key => $wrappedGoal) {
			if ($wrappedGoal->getGoal() === $goal) {
				if ($wrappedGoal->isRunning()) {
					$wrappedGoal->stop();
				}
				unset($this->availableGoals[$key]);
			}
		}
	}

	/**
	 * @param array<int, int> $flags
	 */
	private static function goalContainsAnyFlags(WrappedGoal $wrappedGoal, array $flags) : bool {
		foreach ($wrappedGoal->getFlags() as $flag) {
			if (isset($flags[$flag])) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @param array<int, WrappedGoal> $lockedFlags
	 */
	private static function goalCanBeReplacedForAllFlags(WrappedGoal $wrappedGoal, array $lockedFlags) : bool {
		foreach ($wrappedGoal->getFlags() as $flag) {
			if (isset($lockedFlags[$flag]) && !$lockedFlags[$flag]->canBeReplacedBy($wrappedGoal)) {
				return false;
			}
		}
		return true;
	}

	public function tick() : void{
		Timings::$goalSelectorCleanup->startTiming();

		foreach ($this->availableGoals as $wrappedGoal) {
			if ($wrappedGoal->isRunning() && (self::goalContainsAnyFlags($wrappedGoal, $this->disabledFlags) || !$wrappedGoal->canContinueToUse())) {
				$wrappedGoal->stop();
			}
		}

		foreach ($this->lockedFlags as $flag => $wrappedGoal) {
			if (!$wrappedGoal->isRunning()) {
				unset($this->lockedFlags[$flag]);
			}
		}

		Timings::$goalSelectorCleanup->stopTiming();

		Timings::$goalSelectorUpdate->startTiming();

		foreach ($this->availableGoals as $wrappedGoal) {
			if (!$wrappedGoal->isRunning() &&
				!self::goalContainsAnyFlags($wrappedGoal, $this->disabledFlags) &&
				self::goalCanBeReplacedForAllFlags($wrappedGoal, $this->lockedFlags) &&
				$wrappedGoal->canUse()
			) {
				foreach ($wrappedGoal->getFlags() as $flag) {
					if (isset($this->lockedFlags[$flag])) {
						$this->lockedFlags[$flag]->stop();
					}
					$this->lockedFlags[$flag] = $wrappedGoal;
				}

				$wrappedGoal->start();
			}
		}

		Timings::$goalSelectorUpdate->stopTiming();

		$this->tickRunningGoals(true);
	}

	public function tickRunningGoals(bool $force = false) : void{
		Timings::$goalSelectorTick->startTiming();

		foreach ($this->availableGoals as $wrappedGoal) {
			if ($wrappedGoal->isRunning() && ($force || $wrappedGoal->requiresUpdateEveryTick())) {
				$wrappedGoal->tick();
			}
		}

		Timings::$goalSelectorTick->stopTiming();
	}

	/**
	 * @return WrappedGoal[]
	 */
	public function getAvailableGoals() : array{
		return $this->availableGoals;
	}

	/**
	 * @return WrappedGoal[]
	 */
	public function getRunningGoals() : array{
		return array_filter($this->availableGoals, static function(WrappedGoal $wrappedGoal) : bool{
			return $wrappedGoal->isRunning();
		});
	}

	public function setNewGoalRate(int $rate) : void{
		$this->newGoalRate = $rate;
	}

	public function disableControlFlag(int $flag) : void{
		if ($flag < Goal::FLAG_MOVE || $flag > Goal::FLAG_TARGET) {
			throw new InvalidArgumentException("Invalid goal flag");
		}
		$this->disabledFlags[$flag] = $flag;
	}

	public function enableControlFlag(int $flag) : void{
		if ($flag < Goal::FLAG_MOVE || $flag > Goal::FLAG_TARGET) {
			throw new InvalidArgumentException("Invalid goal flag");
		}
		unset($this->disabledFlags[$flag]);
	}

	public function setControlFlag(int $flag, bool $enabled) : void{
		if ($enabled) {
			$this->enableControlFlag($flag);
		} else {
			$this->disableControlFlag($flag);
		}
	}
}
