<?php

namespace pocketmine\world\generator\overworld\populator\biome\utils;

use pocketmine\world\generator\object\OreType;

final class OreTypeHolder{

	public function __construct(
		public OreType $type,
		public int $value
	){}
}