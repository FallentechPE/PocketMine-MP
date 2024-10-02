<?php

namespace pocketmine\block;


use pocketmine\item\Item;
use pocketmine\item\ItemTypeIds;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\sound\CauldronEmptyPowderSnowSound;
use pocketmine\world\sound\CauldronFillPowderSnowSound;
use pocketmine\world\sound\Sound;

final class PowderSnowCauldron extends FillableCauldron{

	public function getFillSound() : Sound{
		return new CauldronFillPowderSnowSound();
	}

	public function getEmptySound() : Sound{
		return new CauldronEmptyPowderSnowSound();
	}

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []) : bool{
		match($item->getTypeId()){
			ItemTypeIds::BUCKET => $this->removeFillLevels(self::MAX_FILL_LEVEL, $item, VanillaItems::POWDER_SNOW_BUCKET(), $returnedItems),
			ItemTypeIds::POWDER_SNOW_BUCKET, ItemTypeIds::WATER_BUCKET => $this->mix($item, VanillaItems::BUCKET(), $returnedItems),
			ItemTypeIds::LINGERING_POTION, ItemTypeIds::POTION, ItemTypeIds::SPLASH_POTION => $this->mix($item, VanillaItems::GLASS_BOTTLE(), $returnedItems),
			default => null
		};
		return true;
	}

	public function hasEntityCollision() : bool{ return true; }
}