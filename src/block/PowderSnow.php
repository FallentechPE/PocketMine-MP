<?php

namespace pocketmine\block;

use pocketmine\world\sound\BucketEmptyPowderSnowSound;
use pocketmine\world\sound\BucketFillPowderSnowSound;
use pocketmine\world\sound\Sound;

class PowderSnow extends Transparent{
	//todo The block recovery logic via bucket is missing. The fact that the player slowly falls in with an effect is also missing.

	public function getBucketFillSound() : Sound{
		return new BucketFillPowderSnowSound();
	}

	public function getBucketEmptySound() : Sound{
		return new BucketEmptyPowderSnowSound();
	}

}