<?php

namespace pocketmine\block\utils;

use pocketmine\utils\LegacyEnumShimTrait;

/**
 * TODO: These tags need to be removed once we get rid of LegacyEnumShimTrait (PM6)
 *  These are retained for backwards compatibility only.
 *
 * @method static DripstoneThickness MERGE()
 * @method static DripstoneThickness TIP()
 * @method static DripstoneThickness FRUSTUM()
 * @method static DripstoneThickness MIDDLE()
 * @method static DripstoneThickness BASE()
 */
enum DripstoneThickness{
	use LegacyEnumShimTrait;

	case MERGE;
	case TIP;
	case FRUSTUM;
	case MIDDLE;
	case BASE;
}
