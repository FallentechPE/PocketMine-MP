<?php

namespace pocketmine\data\bedrock;

use InvalidArgumentException;
use pocketmine\entity\animal\MooshroomCowType;
use pocketmine\utils\SingletonTrait;

final class MooshroomCowTypeIdMap{
	use SingletonTrait;

	/**
	 * @var MooshroomCowType[]
	 * @phpstan-var array<int, MooshroomCowType>
	 */
	private array $idToEnum = [];

	/**
	 * @var int[]
	 * @phpstan-var array<int, int>
	 */
	private array $enumToId = [];

	private function __construct(){
		$this->register(MooshroomCowTypeIds::RED, MooshroomCowType::RED());
		$this->register(MooshroomCowTypeIds::BROWN, MooshroomCowType::BROWN());
	}

	private function register(int $id, MooshroomCowType $type) : void{
		$this->idToEnum[$id] = $type;
		$this->enumToId[$type->id()] = $id;
	}

	public function fromId(int $id) : ?MooshroomCowType{
		return $this->idToEnum[$id] ?? null;
	}

	public function toId(MooshroomCowType $type) : int{
		if(!isset($this->enumToId[$type->id()])){
			throw new InvalidArgumentException("Type does not have a mapped ID");
		}
		return $this->enumToId[$type->id()];
	}
}
