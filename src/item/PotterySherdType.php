<?php

namespace pocketmine\item;

use pocketmine\utils\LegacyEnumShimTrait;

/**
 * TODO: These tags need to be removed once we get rid of LegacyEnumShimTrait (PM6)
 *  These are retained for backwards compatibility only.
 *
 * @method static PotterySherdType ANGLER()
 * @method static PotterySherdType ARCHER()
 * @method static PotterySherdType ARMS_UP()
 * @method static PotterySherdType BLADE()
 * @method static PotterySherdType BREWER()
 * @method static PotterySherdType BURN()
 * @method static PotterySherdType DANGER()
 * @method static PotterySherdType EXPLORER()
 * @method static PotterySherdType FLOW()
 * @method static PotterySherdType FRIEND()
 * @method static PotterySherdType GUSTER()
 * @method static PotterySherdType HEART()
 * @method static PotterySherdType HEARTBREAK()
 * @method static PotterySherdType HOWL()
 * @method static PotterySherdType MINER()
 * @method static PotterySherdType MOURNER()
 * @method static PotterySherdType PLENTY()
 * @method static PotterySherdType PRIZE()
 * @method static PotterySherdType SCRAPE()
 * @method static PotterySherdType SHEAF()
 * @method static PotterySherdType SHELTER()
 * @method static PotterySherdType SKULL()
 * @method static PotterySherdType SNORT()
 */
enum PotterySherdType{
	use LegacyEnumShimTrait;

	case ANGLER;
	case ARCHER;
	case ARMS_UP;
	case BLADE;
	case BREWER;
	case BURN;
	case DANGER;
	case EXPLORER;
	case FLOW;
	case FRIEND;
	case GUSTER;
	case HEART;
	case HEARTBREAK;
	case HOWL;
	case MINER;
	case MOURNER;
	case PLENTY;
	case PRIZE;
	case SCRAPE;
	case SHEAF;
	case SHELTER;
	case SKULL;
	case SNORT;

	public function getDisplayName() : string{
		return match($this){
			self::ANGLER => "Angler",
			self::ARCHER => "Archer",
			self::ARMS_UP => "Arms Up",
			self::BLADE => "Blade",
			self::BREWER => "Brewer",
			self::BURN => "Burn",
			self::DANGER => "Danger",
			self::EXPLORER => "Explorer",
			self::FLOW => "Flow",
			self::FRIEND => "Friend",
			self::GUSTER => "Guster",
			self::HEART => "Heart",
			self::HEARTBREAK => "Heartbreak",
			self::HOWL => "Howl",
			self::MINER => "Miner",
			self::MOURNER => "Mourner",
			self::PLENTY => "Plenty",
			self::PRIZE => "Prize",
			self::SCRAPE => "Scrape",
			self::SHEAF => "Sheaf",
			self::SHELTER => "Shelter",
			self::SKULL => "Skull",
			self::SNORT => "Snort",
		};
	}
}
