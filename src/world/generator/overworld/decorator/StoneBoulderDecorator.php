<?php

namespace pocketmine\world\generator\overworld\decorator;

use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use pocketmine\world\format\Chunk;
use pocketmine\world\generator\object\StoneBoulder;
use pocketmine\world\generator\populator\Decorator;

class StoneBoulderDecorator extends Decorator{

	public function populate(ChunkManager $world, Random $random, int $chunk_x, int $chunk_z, Chunk $chunk) : void{
		$source_x = $chunk_x << Chunk::COORD_BIT_SIZE;
		$source_z = $chunk_z << Chunk::COORD_BIT_SIZE;
		for ($i = 0; $i < $random->nextBoundedInt(3); ++$i) {
			$x = $source_x + $random->nextBoundedInt(16);
			$z = $source_z + $random->nextBoundedInt(16);
			$y = $chunk->getHighestBlockAt($x & Chunk::COORD_MASK, $z & Chunk::COORD_MASK);
			(new StoneBoulder())->generate($world, $random, $x, $y, $z);
		}
	}

	public function decorate(ChunkManager $world, Random $random, int $chunk_x, int $chunk_z, Chunk $chunk) : void{
	}
}