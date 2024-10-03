<?php

namespace pocketmine\block\tile;

use pocketmine\math\AxisAlignedBB;
use pocketmine\utils\SingletonTrait;

final class SpawnerSpawnRangeRegistry{
	use SingletonTrait;

	/**
	 * @phpstan-var array<string, AxisAlignedBB>
	 * @var AxisAlignedBB[]
	 */
	private array $spawnRange = [];

	public function register(string $entitySaveId, AxisAlignedBB $spawnRange) : void{
		if(isset($this->spawnRange[$entitySaveId])){
			throw new \InvalidArgumentException("Spawn range for entity $entitySaveId is already registered");
		}
		$this->spawnRange[$entitySaveId] = $spawnRange;
	}

	public function getSpawnRange(string $entitySaveId) : ?AxisAlignedBB{
		$spawnRange = $this->spawnRange[$entitySaveId] ?? null;
		return $spawnRange === null ? null : clone $spawnRange;
	}
}