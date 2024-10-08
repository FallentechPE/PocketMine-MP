<?php

namespace pocketmine\entity\animal\utils;

use InvalidArgumentException;
use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\SuspiciousStewType;
use pocketmine\utils\SingletonTrait;

final class SuspiciousStewTypeFlowerMap{
	use SingletonTrait;

	/**
	 * @var SuspiciousStewType[]
	 * @phpstan-var array<int, SuspiciousStewType>
	 */
	private array $flowerToEnum = [];

	/**
	 * @var Block[]
	 * @phpstan-var array<int, Block>
	 */
	private array $enumToFlower = [];

	private function __construct(){
		$this->register(VanillaBlocks::POPPY(), SuspiciousStewType::POPPY());
		$this->register(VanillaBlocks::CORNFLOWER(), SuspiciousStewType::CORNFLOWER());

		$this->register(VanillaBlocks::ORANGE_TULIP(), SuspiciousStewType::TULIP());
		$this->register(VanillaBlocks::PINK_TULIP(), SuspiciousStewType::TULIP());
		$this->register(VanillaBlocks::RED_TULIP(), SuspiciousStewType::TULIP());
		$this->register(VanillaBlocks::WHITE_TULIP(), SuspiciousStewType::TULIP());

		$this->register(VanillaBlocks::AZURE_BLUET(), SuspiciousStewType::AZURE_BLUET());
		$this->register(VanillaBlocks::LILY_OF_THE_VALLEY(), SuspiciousStewType::LILY_OF_THE_VALLEY());
		$this->register(VanillaBlocks::DANDELION(), SuspiciousStewType::DANDELION());
		$this->register(VanillaBlocks::BLUE_ORCHID(), SuspiciousStewType::BLUE_ORCHID());
		$this->register(VanillaBlocks::ALLIUM(), SuspiciousStewType::ALLIUM());
		$this->register(VanillaBlocks::OXEYE_DAISY(), SuspiciousStewType::OXEYE_DAISY());
		$this->register(VanillaBlocks::WITHER_ROSE(), SuspiciousStewType::WITHER_ROSE());
	}

	private function register(Block $flower, SuspiciousStewType $type) : void{
		$this->flowerToEnum[$flower->getTypeId()] = $type;
		$this->enumToFlower[$type->id()] = $flower;
	}

	public function fromFlower(Block $flower) : ?SuspiciousStewType{
		return $this->flowerToEnum[$flower->getTypeId()] ?? null;
	}

	public function toFlower(SuspiciousStewType $type) : Block{
		if(!isset($this->enumToFlower[$type->id()])){
			throw new InvalidArgumentException("Type does not have a mapped ID");
		}
		return $this->enumToFlower[$type->id()];
	}
}
