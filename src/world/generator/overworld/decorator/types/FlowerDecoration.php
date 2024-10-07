<?php

namespace pocketmine\world\generator\overworld\decorator\types;

use pocketmine\block\Block;

final class FlowerDecoration{

	public function __construct(
		readonly public Block $block,
		readonly public int $weight
	){}
}