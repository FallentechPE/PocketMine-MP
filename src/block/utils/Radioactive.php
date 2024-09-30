<?php

namespace pocketmine\block\utils;

use pocketmine\math\AxisAlignedBB;

interface Radioactive
{
	public function getRadioactivityRadius(): float;

	public function getRadioactivityStrength(): float;

	public function tickRadioactivity(AxisAlignedBB $bb): bool;
}