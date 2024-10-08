<?php

namespace pocketmine\entity\ai\memory;

class ExpirableValue {

	private mixed $value;

	private ?int $timeToLive;

	public function __construct(mixed $value, ?int $timeToLive = null) {
		$this->value = $value;
		$this->timeToLive = $timeToLive;
	}

	public function tick() : void {
		if ($this->canExpire()) {
			--$this->timeToLive;
		}
	}

	public function getValue() : mixed {
		return $this->value;
	}

	public function hasExpired() : bool {
		if ($this->canExpire()) {
			return $this->timeToLive <= 0;
		}
		return false;
	}

	public function canExpire() : bool {
		return $this->timeToLive !== null;
	}
}
