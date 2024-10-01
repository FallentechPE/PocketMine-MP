<?php

namespace pocketmine\item;

use pocketmine\utils\EnumTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static ItemLockMode INVENTORY()
 * @method static ItemLockMode SLOT()
 */
final class ItemLockMode{
	use EnumTrait;

	protected static function setup() : void{
		self::registerAll(
			new self("slot"),
			new self("inventory")
		);
	}
}