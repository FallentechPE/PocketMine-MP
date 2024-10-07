<?php

namespace pocketmine\world\generator\overworld\populator\biome;

use pocketmine\world\generator\object\tree\MegaSpruceTree;
use pocketmine\world\generator\object\tree\RedwoodTree;
use pocketmine\world\generator\object\tree\TallRedwoodTree;
use pocketmine\world\generator\overworld\biome\BiomeIds;
use pocketmine\world\generator\overworld\decorator\types\TreeDecoration;

class MegaSpruceTaigaPopulator extends MegaTaigaPopulator{

	/** @var TreeDecoration[] */
	protected static array $TREES;

	protected static function initTrees() : void{
		self::$TREES = [
			new TreeDecoration(RedwoodTree::class, 44),
			new TreeDecoration(TallRedwoodTree::class, 22),
			new TreeDecoration(MegaSpruceTree::class, 33)
		];
	}

	public function getBiomes() : ?array{
		return [BiomeIds::REDWOOD_TAIGA_MUTATED, BiomeIds::REDWOOD_TAIGA_HILLS_MUTATED];
	}

	protected function initPopulators() : void{
		$this->tree_decorator->setTrees(...self::$TREES);
	}
}

MegaSpruceTaigaPopulator::init();