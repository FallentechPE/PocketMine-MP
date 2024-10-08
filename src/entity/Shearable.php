<?php

namespace pocketmine\entity;

interface Shearable {

	public function shear() : void;

	public function setSheared(bool $sheared = true) : void;

	public function isSheared() : bool;

	public function isReadyForShearing() : bool;
}
