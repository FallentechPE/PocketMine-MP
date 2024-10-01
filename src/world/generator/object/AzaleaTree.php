<?php

namespace pocketmine\world\generator\object;

use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\utils\Random;
use pocketmine\world\BlockTransaction;
use pocketmine\world\ChunkManager;

class AzaleaTree extends Tree{ // todo finish this tree


	public function __construct(){
		parent::__construct(VanillaBlocks::OAK_LOG(), VanillaBlocks::AZALEA_LEAVES()); // todo add multiple leaves support
	}

	public function getBlockTransaction(ChunkManager $world, int $x, int $y, int $z, Random $random) : ?BlockTransaction{
		$this->treeHeight = $random->nextBoundedInt(3) + 4;
		return parent::getBlockTransaction($world, $x, $y, $z, $random);
	}

	protected function placeCanopy(int $x, int $y, int $z, Random $random, BlockTransaction $transaction) : void{
		for($yy = $y - 3 + $this->treeHeight; $yy <= $y + $this->treeHeight; ++$yy){
			$yOff = $yy - ($y + $this->treeHeight);
			$mid = (int) (1 - $yOff / 2);
			for($xx = $x - $mid; $xx <= $x + $mid; ++$xx){
				$xOff = abs($xx - $x);
				for($zz = $z - $mid; $zz <= $z + $mid; ++$zz){
					$zOff = abs($zz - $z);
					if($xOff === $mid && $zOff === $mid && ($yOff === 0 || $random->nextBoundedInt(2) === 0)){
						continue;
					}
					if(!$transaction->fetchBlockAt($xx, $yy, $zz)->isSolid()){
						$transaction->addBlockAt($xx, $yy, $zz, $random->nextFloat() < 0.33 ? VanillaBlocks::FLOWERING_AZALEA_LEAVES() : VanillaBlocks::AZALEA_LEAVES());
					}
				}
			}
		}
	}
}
