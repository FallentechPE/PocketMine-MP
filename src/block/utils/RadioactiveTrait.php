<?php

namespace pocketmine\block\utils;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\world\Position;

trait RadioactiveTrait {

	abstract protected function getPosition(): Position;

	public function getRadioactivityStrength(): float {
		return 1000.0;
	}

	public function onRandomTick(): void {
		$pos = $this->getPosition();
		$world = $pos->getWorld();
		$block = $world->getBlock($pos);
		foreach ($world->getPlayers() as $player) {
			$distance = $player->getPosition()->distance($block->getPosition());
			$damage = $this->calculateDamage($distance);
			var_dump($damage);
			if ($damage != 0.0) {
				$ev = new EntityDamageEvent($player, EntityDamageEvent::CAUSE_RADIOACTIVITY, $damage);
				$player->attack($ev);
			}
		}
	}

	private function calculateDamage(float $distance): float {
		$pi = pi();
		$expTerm = exp(-0.0125 * $distance);
		$radiationDamage = ($this->getRadioactivityStrength() / (4 * $pi * pow($distance, 2))) * $expTerm;
		return $radiationDamage < 0.2 ? 0.0 : min($radiationDamage, 15);
	}

}