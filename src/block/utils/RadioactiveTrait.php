<?php

namespace pocketmine\block\utils;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\AxisAlignedBB;
use pocketmine\player\Player;
use pocketmine\world\Position;

trait RadioactiveTrait
{

	abstract protected function getPosition(): Position;

	public function getRadioactivityRadius(): float
	{
		return 1.0;
	}

	public function getRadioactivityStrength(): float
	{
		return 1.0;
	}

	public function tickRadioactivity(AxisAlignedBB $bb): bool
	{
		$pos = $this->getPosition();
		$world = $pos->getWorld();
		$block = $world->getBlock($pos);
		foreach ($block->getCollisionBoxes() as $bb2) {
			$bb = $bb->expandedCopy($this->getRadioactivityRadius() * 2, $this->getRadioactivityRadius() * 2, $this->getRadioactivityRadius() * 2);
			if ($bb->intersectsWith($bb2)) {
				foreach ($world->getCollidingEntities($bb) as $player) {
					if ($player instanceof Player) {
						$ev = new EntityDamageEvent($player, EntityDamageEvent::CAUSE_RADIOACTIVITY, $this->getRadioactivityStrength() / 20);
						$player->attack($ev);
					}
				}
			}
		}

		return false;
	}

}