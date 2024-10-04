<?php

namespace pocketmine\block\utils;

use pocketmine\utils\LegacyEnumShimTrait;

/**
 * TODO: These tags need to be removed once we get rid of LegacyEnumShimTrait (PM6)
 *  These are retained for backwards compatibility only.
 *
 * @method static TurtleEggCount ONE_EGG()
 * @method static TurtleEggCount TWO_EGG()
 * @method static TurtleEggCount THREE_EGG()
 * @method static TurtleEggCount FOUR_EGG()
 */
enum TurtleEggCount{
	use LegacyEnumShimTrait;

	case ONE_EGG;
	case TWO_EGG;
	case THREE_EGG;
	case FOUR_EGG;

	public function getCount() : int{
		return match($this){
			self::ONE_EGG => 1,
			self::TWO_EGG => 2,
			self::THREE_EGG => 3,
			self::FOUR_EGG => 4,
		};
	}
}
