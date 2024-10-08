<?php

namespace pocketmine\entity\pathfinder;

use pocketmine\utils\EnumTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static PathComputationType AIR()
 * @method static PathComputationType LAND()
 * @method static PathComputationType WATER()
 */
final class PathComputationType{
	use EnumTrait;

	protected static function setup() : void{
		self::registerAll(
			new self("land"),
			new self("water"),
			new self("air")
		);
	}
}
