<?php

namespace pocketmine\world\generator\ground;

use pocketmine\block\VanillaBlocks;

class MycelGroundGenerator extends GroundGenerator{

	public function __construct(){
		parent::__construct(VanillaBlocks::MYCELIUM());
	}
}