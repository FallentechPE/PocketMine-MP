<?php

namespace pocketmine\entity;

use pocketmine\utils\EnumTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static MobCategory AMBIENT()
 * @method static MobCategory AXOLOTLS()
 * @method static MobCategory CREATURE()
 * @method static MobCategory MISC()
 * @method static MobCategory MONSTER()
 * @method static MobCategory UNDERGROUND_WATER_CREATURE()
 * @method static MobCategory WATER_AMBIENT()
 * @method static MobCategory WATER_CREATURE()
 */
final class MobCategory {
	use EnumTrait {
		__construct as Enum___construct;
	}

	protected static function setup() : void{
		self::registerAll(
			new self("monster", 70, false, false, 64),
			new self("creature", 10, true, true, 64),
			new self("ambient", 15, true, false, 32),
			new self("axolotls", 5, true, false, 64),
			new self("underground_water_creature", 5, true, false, 64),
			new self("water_creature", 5, true, false, 64),
			new self("water_ambient", 20, true, false, 40),
			new self("misc", -1, true, true, 64)
		);
	}

	private function __construct(
		string $enumName,
		private int $max,
		private bool $isFriendly,
		private bool $isPersistent,
		private int $despawnDistance
	){
		$this->Enum___construct($enumName);
	}

	public function getMaxInstancesPerChunk() : int{
		return $this->max;
	}

	public function isFriendly() : bool{
		return $this->isFriendly;
	}

	public function isPersistent() : bool{
		return $this->isPersistent;
	}

	public function getDespawnDistance() : int{
		return $this->despawnDistance;
	}

	public function getNoDespawnDistance() : int{
		return 32;
	}
}
