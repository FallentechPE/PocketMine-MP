<?php

namespace pocketmine\world\generator\ground;

use pocketmine\block\VanillaBlocks;

class RockyGroundGenerator extends GroundGenerator{

	public function __construct(){
		parent::__construct(VanillaBlocks::STONE(), VanillaBlocks::STONE());
	}
}