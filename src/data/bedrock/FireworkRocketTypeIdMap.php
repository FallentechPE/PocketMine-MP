<?php

namespace pocketmine\data\bedrock;

use pocketmine\item\FireworkRocketType;
use pocketmine\utils\SingletonTrait;

final class FireworkRocketTypeIdMap{
	use SingletonTrait;
	/** @phpstan-use IntSaveIdMapTrait<FireworkRocketType> */
	use IntSaveIdMapTrait;

	private function __construct(){
		foreach(FireworkRocketType::cases() as $case){
			$this->register(match($case){
				FireworkRocketType::SMALL_BALL => FireworkRocketTypeIds::SMALL_BALL,
				FireworkRocketType::LARGE_BALL => FireworkRocketTypeIds::LARGE_BALL,
				FireworkRocketType::STAR => FireworkRocketTypeIds::STAR,
				FireworkRocketType::CREEPER => FireworkRocketTypeIds::CREEPER,
				FireworkRocketType::BURST => FireworkRocketTypeIds::BURST,
			}, $case);
		}
	}
}