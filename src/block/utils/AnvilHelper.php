<?php

namespace pocketmine\block\utils;

use pocketmine\block\anvil\AnvilActionsFactory;
use pocketmine\item\Item;
use pocketmine\player\Player;
use function max;

final class AnvilHelper{
	private const COST_LIMIT = 39;

	/**
	 * Attempts to calculate the result of an anvil operation.
	 *
	 * Returns null if the operation can't do anything.
	 */
	public static function calculateResult(Player $player, Item $base, Item $material, ?string $customName = null) : ?AnvilResult {
		$xpCost = 0;
		$resultItem = clone $base;

		$additionnalRepairCost = 0;
		foreach(AnvilActionsFactory::getInstance()->getActions($base, $material, $customName) as $action){
			$action->process($resultItem);
			if(!$action->isFreeOfRepairCost() && $action->getXpCost() > 0){
				// Repair cost increment if the item has been processed
				// and any of the action is not free of repair cost
				$additionnalRepairCost = 1;
			}
			$xpCost += $action->getXpCost();
		}

		$xpCost += 2 ** $resultItem->getRepairCost() - 1;
		$xpCost += 2 ** $material->getRepairCost() - 1;
		$resultItem->setRepairCost(
			max($resultItem->getRepairCost(), $material->getRepairCost()) + $additionnalRepairCost
		);

		if($xpCost <= 0 || ($xpCost > self::COST_LIMIT && !$player->isCreative())){
			return null;
		}

		return new AnvilResult($xpCost, $resultItem);
	}
}