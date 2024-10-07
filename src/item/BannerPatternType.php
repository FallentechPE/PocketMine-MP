<?php

namespace pocketmine\item;

use pocketmine\utils\LegacyEnumShimTrait;

/**
 * TODO: These tags need to be removed once we get rid of LegacyEnumShimTrait (PM6)
 *  These are retained for backwards compatibility only.
 *
 * @method static BannerPatternType FLOWER_CHARGE()
 * @method static BannerPatternType CREEPER_CHARGE()
 * @method static BannerPatternType SKULL_CHARGE()
 * @method static BannerPatternType MOJANG()
 * @method static BannerPatternType GLOBE()
 * @method static BannerPatternType PIGLIN()
 * @method static BannerPatternType FLOW()
 * @method static BannerPatternType GUSTER()
 * @method static BannerPatternType FIELD_MASONED()
 * @method static BannerPatternType BORDURE_INDENTED()
 */
enum BannerPatternType{
	use LegacyEnumShimTrait;

	case FLOWER_CHARGE;
	case CREEPER_CHARGE;
	case SKULL_CHARGE;
	case MOJANG;
	case GLOBE;
	case PIGLIN;
	case FLOW;
	case GUSTER;
	case FIELD_MASONED;
	case BORDURE_INDENTED;

	public function getDisplayName() : string{
		return match($this){
			self::FLOWER_CHARGE => "Flower Charge",
			self::CREEPER_CHARGE => "Creeper Charge",
			self::SKULL_CHARGE => "Skull Charge",
			self::MOJANG => "Mojang",
			self::GLOBE => "Globe",
			self::PIGLIN => "Piglin",
			self::FLOW => "Flow",
			self::GUSTER => "Guster",
			self::FIELD_MASONED => "Field Masoned",
			self::BORDURE_INDENTED => "Bordure Indented",
		};
	}
}
