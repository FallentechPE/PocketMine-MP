<?php

namespace pocketmine\entity\schedule;

class ActivityTransition{

	private int $time;

	private Activity $activity;

	public function __construct(int $time, Activity $activity){
		$this->time = $time;
		$this->activity = $activity;
	}

	public function getTime() : int{
		return $this->time;
	}

	public function getActivity() : Activity{
		return $this->activity;
	}
}
