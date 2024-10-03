<?php

namespace pocketmine\block\anvil;

use pocketmine\item\Durable;
use pocketmine\item\Item;
use function assert;
use function ceil;
use function floor;
use function max;
use function min;

final class RepairWithMaterialAction extends AnvilAction{
	private const COST = 1;

	public function canBeApplied() : bool{
		return $this->base instanceof Durable &&
			$this->base->isValidRepairMaterial($this->material) &&
			$this->base->getDamage() > 0;
	}

	public function process(Item $resultItem) : void{
		assert($resultItem instanceof Durable, "Result item must be durable");
		assert($this->base instanceof Durable, "Base item must be durable");

		$damage = $this->base->getDamage();
		$quarter = min($damage, (int) floor($this->base->getMaxDurability() / 4));
		$numberRepair = min($this->material->getCount(), (int) ceil($damage / $quarter));
		if($numberRepair > 0){
			$this->material->pop($numberRepair);
			$damage -= $quarter * $numberRepair;
		}
		$resultItem->setDamage(max(0, $damage));

		$this->xpCost = $numberRepair * self::COST;
	}
}