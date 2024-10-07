<?php

namespace pocketmine\world\generator\overworld\biome;

class BiomeClimate{

	public function __construct(
		readonly public float $temperature,
		readonly public float $humidity,
		readonly public bool $rainy
	){}
}