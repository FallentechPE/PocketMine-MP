<?php

namespace pocketmine\world\generator\ground;

use pocketmine\block\VanillaBlocks;

class SandyGroundGenerator extends GroundGenerator{

	public function __construct(){
		parent::__construct(VanillaBlocks::SAND(), VanillaBlocks::SAND());
	}
}