<?php

namespace pocketmine\block\utils;

interface Radioactive
{
	public function getRadioactivityRadius(): float;

	public function getRadioactivityStrength(): float;

	public function tickRadioactivity() : bool;
}