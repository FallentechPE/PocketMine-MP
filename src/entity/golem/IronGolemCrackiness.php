<?php

namespace pocketmine\entity\golem;

use function array_reverse;

enum IronGolemCrackiness{

	case NONE;
	case LOW;
	case MEDIUM;
	case HIGH;

	public static function fromHealthPercentage(float $percentage) : IronGolemCrackiness{
		foreach (array_reverse(IronGolemCrackiness::cases()) as $crackiness) {
			if ($percentage <= $crackiness->getHeathPercentage()) {
				return $crackiness;
			}
		}

		return IronGolemCrackiness::NONE;
	}

	/**
	 * Returns the minimum percentage of life for this state.
	 *
	 * @return float 0.0-1.0
	 */
	public function getHeathPercentage() : float{
		return match($this){
			self::NONE => 1,
			self::LOW => 0.75,
			self::MEDIUM => 0.5,
			self::HIGH => 0.25
		};
	}
}
