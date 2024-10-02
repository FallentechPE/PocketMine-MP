<?php

namespace pocketmine\item;

use pocketmine\world\sound\FireworkExplosionSound;
use pocketmine\world\sound\FireworkLargeExplosionSound;
use pocketmine\world\sound\Sound;

enum FireworkRocketType{
	case SMALL_BALL;
	case LARGE_BALL;
	case STAR;
	case CREEPER;
	case BURST;

	public function getExplosionSound() : Sound{
		return match($this){
			self::SMALL_BALL,
			self::STAR,
			self::CREEPER,
			self::BURST => new FireworkExplosionSound(),
			self::LARGE_BALL => new FireworkLargeExplosionSound(),
		};
	}
}