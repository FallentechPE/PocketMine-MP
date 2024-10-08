<?php

namespace pocketmine\entity;

use pocketmine\utils\EnumTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static MobType ARTHROPOD()
 * @method static MobType ILLAGER()
 * @method static MobType UNDEAD()
 * @method static MobType UNDEFINED()
 * @method static MobType WATER()
 */
final class MobType {
	use EnumTrait;

	protected static function setup() : void{
		self::registerAll(
			new self("undefined"),
			new self("undead"),
			new self("arthropod"),
			new self("illager"),
			new self("water")
		);
	}
}
