<?php

namespace pocketmine\data\bedrock;

use InvalidArgumentException;
use pocketmine\entity\monster\slime\SlimeType;
use pocketmine\utils\SingletonTrait;

final class SlimeTypeIdMap{
	use SingletonTrait;

	/**
	 * @var SlimeType[]
	 * @phpstan-var array<int, SlimeType>
	 */
	private array $idToEnum = [];

	/**
	 * @var int[]
	 * @phpstan-var array<int, int>
	 */
	private array $enumToId = [];

	private function __construct(){
		$this->register(SlimeTypeIds::LARGE, SlimeType::LARGE());
		$this->register(SlimeTypeIds::MEDIUM, SlimeType::MEDIUM());
		$this->register(SlimeTypeIds::SMALL, SlimeType::SMALL());
	}

	private function register(int $id, SlimeType $type) : void{
		$this->idToEnum[$id] = $type;
		$this->enumToId[$type->id()] = $id;
	}

	public function fromId(int $id) : ?SlimeType{
		return $this->idToEnum[$id] ?? null;
	}

	public function toId(SlimeType $type) : int{
		if(!isset($this->enumToId[$type->id()])){
			throw new InvalidArgumentException("Type does not have a mapped ID");
		}
		return $this->enumToId[$type->id()];
	}
}
