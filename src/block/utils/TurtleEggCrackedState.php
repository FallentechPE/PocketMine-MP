<?php

namespace pocketmine\block\utils;

use pocketmine\utils\LegacyEnumShimTrait;

/**
 * TODO: These tags need to be removed once we get rid of LegacyEnumShimTrait (PM6)
 *  These are retained for backwards compatibility only.
 *
 * @method static TurtleEggCrackedState NO_CRACKS()
 * @method static TurtleEggCrackedState CRACKED()
 * @method static TurtleEggCrackedState MAX_CRACKED()
 * @method static TurtleEggCrackedState TWO_WALLS()
 */
enum TurtleEggCrackedState{
	use LegacyEnumShimTrait;

	case NO_CRACKS;
	case CRACKED;
	case MAX_CRACKED;
}
