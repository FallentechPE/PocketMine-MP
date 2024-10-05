<?php

namespace pocketmine\block\utils;

use pocketmine\utils\LegacyEnumShimTrait;

/**
 * TODO: These tags need to be removed once we get rid of LegacyEnumShimTrait (PM6)
 *  These are retained for backwards compatibility only.
 *
 * @method static VaultState ACTIVE()
 * @method static VaultState EJECTING()
 * @method static VaultState INACTIVE()
 * @method static VaultState UNLOCKING()
 */
enum VaultState : int{
	use LegacyEnumShimTrait;

	case ACTIVE = 0;
	case EJECTING = 1;
	case INACTIVE = 2;
	case UNLOCKING = 3;
}
