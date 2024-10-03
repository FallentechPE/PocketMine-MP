<?php

namespace pocketmine\item;

use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\utils\SingletonTrait;

final class SpawnEggEntityRegistry{
	use SingletonTrait;

	/**
	 * @phpstan-var array<int, string>
	 * @var string[]
	 */
	private array $entityMap = [];

	private function __construct(){
		$this->register(EntityIds::SQUID, VanillaItems::SQUID_SPAWN_EGG());
		$this->register(EntityIds::VILLAGER, VanillaItems::VILLAGER_SPAWN_EGG());
		$this->register(EntityIds::ZOMBIE, VanillaItems::ZOMBIE_SPAWN_EGG());
	}

	public function register(string $entitySaveId, SpawnEgg $spawnEgg) : void{
		$this->entityMap[$spawnEgg->getTypeId()] = $entitySaveId;
	}

	public function getEntityId(SpawnEgg $item) : ?string{
		return $this->entityMap[$item->getTypeId()] ?? null;
	}
}