<?php

namespace pocketmine\entity;

interface ItemSteerable {

	public function isSteerItem() : bool;

	public function boost() : bool;
}
