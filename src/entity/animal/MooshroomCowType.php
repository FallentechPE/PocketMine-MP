<?php

namespace pocketmine\entity\animal;

use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\utils\EnumTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static MooshroomCowType BROWN()
 * @method static MooshroomCowType RED()
 */
final class MooshroomCowType{
	use EnumTrait {
		__construct as Enum___construct;
	}

	protected static function setup() : void{
		self::registerAll(
			new self("red", VanillaBlocks::RED_MUSHROOM()),
			new self("brown", VanillaBlocks::BROWN_MUSHROOM())
		);
	}

	private function __construct(
		string $enumName,
		private Block $mushroom
	){
		$this->Enum___construct($enumName);
	}

	public function getMushroom() : Block{
		return clone $this->mushroom;
	}
}
