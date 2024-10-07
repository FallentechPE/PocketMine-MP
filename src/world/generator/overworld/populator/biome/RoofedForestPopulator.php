<?php

namespace pocketmine\world\generator\overworld\populator\biome;

use pocketmine\world\generator\object\tree\BirchTree;
use pocketmine\world\generator\object\tree\BrownMushroomTree;
use pocketmine\world\generator\object\tree\DarkOakTree;
use pocketmine\world\generator\object\tree\GenericTree;
use pocketmine\world\generator\object\tree\RedMushroomTree;
use pocketmine\world\generator\overworld\biome\BiomeIds;
use pocketmine\world\generator\overworld\decorator\types\TreeDecoration;

class RoofedForestPopulator extends ForestPopulator{

	private const BIOMES = [BiomeIds::ROOFED_FOREST, BiomeIds::ROOFED_FOREST_MUTATED];

	/** @var TreeDecoration[] */
	protected static array $TREES;

	protected static function initTrees() : void{
		self::$TREES = [
			new TreeDecoration(GenericTree::class, 20),
			new TreeDecoration(BirchTree::class, 5),
			new TreeDecoration(RedMushroomTree::class, 2),
			new TreeDecoration(BrownMushroomTree::class, 2),
			new TreeDecoration(DarkOakTree::class, 50)
		];
	}

	protected function initPopulators() : void{
		$this->tree_decorator->setAmount(50);
		$this->tree_decorator->setTrees(...self::$TREES);
		$this->tall_grass_decorator->setAmount(4);
	}

	public function getBiomes() : ?array{
		return self::BIOMES;
	}
}

RoofedForestPopulator::init();