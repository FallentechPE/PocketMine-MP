<?php

namespace pocketmine\block\utils;

use pocketmine\block\Water;

interface Waterloggable{
	public function getWaterState() : ?Water;

	public function setWaterState(?Water $state) : Waterloggable;
}