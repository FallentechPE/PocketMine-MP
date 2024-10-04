<?php

namespace pocketmine\block\utils;

use pocketmine\utils\LegacyEnumShimTrait;

/**
 * TODO: These tags need to be removed once we get rid of LegacyEnumShimTrait (PM6)
 *  These are retained for backwards compatibility only.
 *
 * @method static BellAttachmentType MERGE()
 * @method static BellAttachmentType TIP()
 * @method static BellAttachmentType FRUSTUM()
 * @method static BellAttachmentType MIDDLE()
 * @method static BellAttachmentType BASE()
 */
enum DripstoneThickness{
	use LegacyEnumShimTrait;

	case MERGE;
	case TIP;
	case FRUSTUM;
	case MIDDLE;
	case BASE;
}
