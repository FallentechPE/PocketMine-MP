<?php

namespace pocketmine\data\bedrock;

use pocketmine\item\GoatHornType;
use pocketmine\utils\SingletonTrait;

final class GoatHornTypeIdMap{
	use SingletonTrait;
	/** @phpstan-use IntSaveIdMapTrait<GoatHornType> */
	use IntSaveIdMapTrait;

	private function __construct(){
		foreach(GoatHornType::cases() as $case){
			$this->register(match($case){
				GoatHornType::PONDER => GoatHornTypeIds::PONDER,
				GoatHornType::SING => GoatHornTypeIds::SING,
				GoatHornType::SEEK => GoatHornTypeIds::SEEK,
				GoatHornType::FEEL => GoatHornTypeIds::FEEL,
				GoatHornType::ADMIRE => GoatHornTypeIds::ADMIRE,
				GoatHornType::CALL => GoatHornTypeIds::CALL,
				GoatHornType::YEARN => GoatHornTypeIds::YEARN,
				GoatHornType::DREAM => GoatHornTypeIds::DREAM
			}, $case);
		}
	}
}