<?php

namespace pocketmine\block\utils;

use pocketmine\utils\LegacyEnumShimTrait;

/**
 * TODO: These tags need to be removed once we get rid of LegacyEnumShimTrait (PM6)
 *  These are retained for backwards compatibility only.
 *
 * @method static CrackedState NO_CRACKS()
 * @method static CrackedState CRACKED()
 * @method static CrackedState MAX_CRACKED()
 */
enum CrackedState{
	use LegacyEnumShimTrait;

	case NO_CRACKS;
	case CRACKED;
	case MAX_CRACKED;
}
