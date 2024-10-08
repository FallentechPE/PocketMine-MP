<?php

namespace pocketmine\entity\ai\behavior;

use pocketmine\utils\EnumTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static BehaviorStatus RUNNING()
 * @method static BehaviorStatus STOPPED()
 */
class BehaviorStatus {
	use EnumTrait;

	protected static function setup() : void{
		self::registerAll(
			new self("stopped"),
			new self("running")
		);
	}
}
