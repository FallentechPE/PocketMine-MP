<?php

namespace pocketmine\block\utils;

trait ElementTrait {

	abstract public function getMeltingPoint() : ?float;

	abstract public function getBoilingPoint() : ?float;

	abstract public function getDensity() : ?float;
}