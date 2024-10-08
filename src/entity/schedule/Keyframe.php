<?php

namespace pocketmine\entity\schedule;

class KeyFrame{

	private int $timeStamp;

	private float $value;

	public function __construct(int $timeStamp, float $value){
		$this->timeStamp = $timeStamp;
		$this->value = $value;
	}

	public function getTimeStamp() : int{
		return $this->timeStamp;
	}

	public function getValue() : float{
		return $this->value;
	}
}
