<?php

namespace pocketmine\block\utils;

use pocketmine\utils\LegacyEnumShimTrait;

/**
 * TODO: These tags need to be removed once we get rid of LegacyEnumShimTrait (PM6)
 *  These are retained for backwards compatibility only.
 *
 * @method static StructureType CORNER()
 * @method static StructureType DATA()
 * @method static StructureType EXPORT()
 * @method static StructureType INVALID()
 * @method static StructureType LOAD()
 * @method static StructureType SAVE()
 */
enum StructureType{
	use LegacyEnumShimTrait;

	case CORNER;
	case DATA;
	case EXPORT;
	case INVALID;
	case LOAD;
	case SAVE;
}
