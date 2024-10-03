<?php

namespace pocketmine\block\anvil;

use pocketmine\item\Durable;
use pocketmine\item\Item;
use function assert;
use function min;

final class RepairWithSacrificeAction extends AnvilAction{
	private const COST = 2;

	public function canBeApplied() : bool{
		return $this->base instanceof Durable &&
			$this->material instanceof Durable &&
			$this->base->getTypeId() === $this->material->getTypeId();
	}

	public function process(Item $resultItem) : void{
		assert($resultItem instanceof Durable, "Result item must be durable");
		assert($this->base instanceof Durable, "Base item must be durable");
		assert($this->material instanceof Durable, "Material item must be durable");

		if($this->base->getDamage() !== 0){
			$baseMaxDurability = $this->base->getMaxDurability();
			$baseDurability = $baseMaxDurability - $this->base->getDamage();
			$materialDurability = $this->material->getMaxDurability() - $this->material->getDamage();
			$addDurability = (int) ($baseMaxDurability * 12 / 100);

			$newDurability = min($baseMaxDurability, $baseDurability + $materialDurability + $addDurability);

			$resultItem->setDamage($baseMaxDurability - $newDurability);

			$this->xpCost = self::COST;
		}
	}
}