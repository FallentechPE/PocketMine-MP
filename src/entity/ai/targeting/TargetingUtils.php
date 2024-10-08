<?php

namespace pocketmine\entity\ai\targeting;


use pocketmine\block\MobHead;
use pocketmine\block\utils\MobHeadType;
use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\entity\monster\Zombie;
use function count;

class TargetingUtils {

	public static function getVisibilityPercent(Living $entity, ?Entity $target = null) : float {
		$visibilityPercent = 1.0;
		if ($entity->isSneaking()) {
			$visibilityPercent *= 0.8;
		}
		if ($entity->isInvisible()) {
			$percent = self::getArmorCoverPercentage($entity);
			if ($percent < 0.1) {
				$percent = 0.1;
			}
			$visibilityPercent *= 0.7 * $percent;
		}
		if ($target !== null) {
			$head = $entity->getArmorInventory()->getHelmet();
			$headBlock = $head->getBlock();
			if ($headBlock instanceof MobHead) {
				$headType = $headBlock->getMobHeadType();
				if (
					//($target instanceof Skeleton && $headType->equals(MobHeadType::SKELETON())) ||
				($target instanceof Zombie && $headType->equals(MobHeadType::ZOMBIE()))
				) {
					$visibilityPercent *= 0.5;
				}
			}
		}
		return $visibilityPercent;
	}

	public static function getArmorCoverPercentage(Living $entity) : float {
		$inventory = $entity->getArmorInventory();
		$size = $inventory->getSize();
		return ($size > 0) ? (count($inventory->getContents()) / $size) : 0.0;
	}
}
