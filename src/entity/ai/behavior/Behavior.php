<?php

namespace pocketmine\entity\ai\behavior;

use pocketmine\entity\Mob;

class Behavior {

	protected array $entryCondition;

	private BehaviorStatus $status;

	private int $endTimestamp;

	private int $minDuration;

	private int $maxDuration;

	public function __construct(array $entryCondition, int $minDuration, int $maxDuration) {
		$this->status = BehaviorStatus::STOPPED();
		$this->minDuration = $minDuration;
		$this->maxDuration = $maxDuration;
		$this->entryCondition = $entryCondition;
	}

	public function getStatus() : BehaviorStatus {
		return $this->status;
	}

	public function tryStart(Mob $entity, int $time) : bool {
		if ($this->hasRequiredMemories($entity) && $this->checkExtraStartConditions($entity)) {
			$this->status = BehaviorStatus::RUNNING();
			$duration = $this->minDuration + $entity->getRandom()->nextBoundedInt($this->maxDuration + 1 - $this->minDuration);
			$this->endTimestamp = $time + $duration;
			$this->start($entity, $time);
			return true;
		}
		return false;
	}

	protected function start(Mob $entity, int $time) : void {
	}

	public function tickOrStop(Mob $entity, int $time) : void {
		if (!$this->timedOut($time) && $this->canStillUse($entity, $time)) {
			$this->tick($entity, $time);
		} else {
			$this->doStop($entity, $time);
		}
	}

	protected function tick(Mob $entity, int $time) : void {
	}

	public function doStop(Mob $entity, int $time) : void {
		$this->status = BehaviorStatus::STOPPED();
		$this->stop($entity, $time);
	}

	protected function stop(Mob $entity, int $time) : void {
	}

	protected function canStillUse(Mob $entity, int $time) : bool {
		return false;
	}

	protected function timedOut(int $timestamp) : bool {
		return $timestamp > $this->endTimestamp;
	}

	protected function checkExtraStartConditions(Mob $entity) : bool {
		return true;
	}

	protected function hasRequiredMemories(Mob $entity) : bool {
		foreach ($this->entryCondition as $data) {
			//Values:
			//$data[0] = MemoryModuleType
			//$data[1] = MemoryStatus
			if (!$entity->getBrain()->checkMemory($data[0], $data[1])) {
				return false;
			}
		}
		return true;
	}
}
