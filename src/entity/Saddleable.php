<?php

namespace pocketmine\entity;

interface Saddleable {

	public function saddle() : void;

	public function setSaddled(bool $sheared = true) : void;

	public function isSaddled() : bool;

	public function canBeSaddled() : bool;
}
