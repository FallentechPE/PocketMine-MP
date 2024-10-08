<?php

namespace pocketmine\entity\ai\memory;

use pocketmine\utils\EnumTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static MemoryStatus REGISTERED()
 * @method static MemoryStatus VALUE_ABSENT()
 * @method static MemoryStatus VALUE_PRESENT()
 */
class MemoryStatus {
	use EnumTrait;

	protected static function setup() : void{
		self::registerAll(
			new self("value_present"),
			new self("value_absent"),
			new self("registered")
		);
	}
}
