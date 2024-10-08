<?php

namespace pocketmine\entity\ai;

use pocketmine\entity\ai\memory\ExpirableValue;
use pocketmine\entity\ai\memory\MemoryModuleType;

final class MemoryValue {

	private MemoryModuleType $type;

	private ?ExpirableValue $value;

	public static function createUnchecked(MemoryModuleType $type, ?ExpirableValue $value = null) : self {
		return new self($type, $value);
	}

	public function __construct(MemoryModuleType $type, ?ExpirableValue $value = null) {
		$this->type = $type;
		$this->value = $value;
	}

	public function setMemoryInternal(Brain $brain) : void {
		$brain->setMemoryInternal($this->type, $this->value);
	}
}
