<?php

namespace pocketmine\block\utils;

use pocketmine\utils\LegacyEnumShimTrait;

/**
 * TODO: These tags need to be removed once we get rid of LegacyEnumShimTrait (PM6)
 *  These are retained for backwards compatibility only.
 *
 * @method static Orientation DOWN_EAST()
 * @method static Orientation DOWN_NORTH()
 * @method static Orientation DOWN_SOUTH()
 * @method static Orientation DOWN_WEST()
 * @method static Orientation EAST_UP()
 * @method static Orientation NORTH_UP()
 * @method static Orientation SOUTH_UP()
 * @method static Orientation UP_EAST()
 * @method static Orientation UP_NORTH()
 * @method static Orientation UP_SOUTH()
 * @method static Orientation UP_WEST()
 * @method static Orientation WEST_UP;
 */
enum Orientation{
	use LegacyEnumShimTrait;

	case DOWN_EAST;
	case DOWN_NORTH;
	case DOWN_SOUTH;
	case DOWN_WEST;
	case EAST_UP;
	case NORTH_UP;
	case SOUTH_UP;
	case UP_EAST;
	case UP_NORTH;
	case UP_SOUTH;
	case UP_WEST;
	case WEST_UP;
}
