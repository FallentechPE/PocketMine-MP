<?php

namespace pocketmine\world\utils;

use pocketmine\block\Block;
use pocketmine\block\RuntimeBlockStateRegistry;
use pocketmine\block\utils\Waterloggable;
use pocketmine\block\Water;
use pocketmine\world\format\Chunk;

class BlockChunkReader{
	/**
	 * @param int $x The block's X coordinate masked to the chunk's bounds
	 * @param int $z The block's Z coordinate masked to the chunk's bounds
	 */
	public static function getBlock(Chunk $chunk, int $x, int $y, int $z) : Block {
		$block = RuntimeBlockStateRegistry::getInstance()->fromStateId($chunk->getBlockStateId($x, $y, $z));
		if($block instanceof Waterloggable){
			$waterState = RuntimeBlockStateRegistry::getInstance()->fromStateId($chunk->getWaterStateId($x, $y, $z));
			if($waterState instanceof Water){
				$block->setWaterState($waterState);
			}
		}

		return $block;
	}
}