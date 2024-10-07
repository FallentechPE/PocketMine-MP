<?php

namespace pocketmine\world\generator\biome\biomegrid\utils;

use pocketmine\world\generator\biome\biomegrid\MapLayer;

final class MapLayerPair{

	public function __construct(
		public MapLayer $high_resolution,
		public ?MapLayer $low_resolution
	){}
}