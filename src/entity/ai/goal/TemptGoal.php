<?php

namespace pocketmine\entity\ai\goal;

use pocketmine\entity\ai\targeting\TargetingConditions;
use pocketmine\entity\Human;
use pocketmine\entity\Living;
use pocketmine\entity\Location;
use pocketmine\entity\PathfinderMob;
use pocketmine\player\Player;
use pocketmine\utils\ItemSet;
use pocketmine\utils\Utils;
use function abs;

class TemptGoal extends Goal {

	public static function TEMP_TARGETING() : TargetingConditions{
		return (new TargetingConditions())
			->setRange(self::TEMP_RANGE)
			->allowNonAttackable()
			->allowInvulnerable()
			->allowUnseeable();
	}

	public const TEMP_RANGE = 16;

	private TargetingConditions $targetingConditions;

	private Player $player;

	private Location $lastPlayerLocation;

	private int $calmDown = 0;

	private bool $isRunning = false;

	public function __construct(
		protected PathfinderMob $entity,
		protected float $speedModifier,
		protected ItemSet $items,
		protected bool $canScare
	) {
		$this->targetingConditions = static::TEMP_TARGETING()->setValidator($this->shouldFollow(...));

		$this->setFlags(Goal::FLAG_MOVE, Goal::FLAG_LOOK);
	}

	public function canUse() : bool{
		if ($this->calmDown > 0) {
			$this->calmDown--;
			return false;
		}

		$player = Utils::getNearestPlayer($this->entity, self::TEMP_RANGE, $this->targetingConditions);
		if ($player !== null) {
			$this->player = $player;

			return true;
		}

		return false;
	}

	public function canContinueToUse() : bool{
		if ($this->canScare()) {
			$pLocation = $this->player->getLocation();
			if ($this->entity->getPosition()->distanceSquared($pLocation) < 36) {
				if ($pLocation->distanceSquared($this->lastPlayerLocation) > 0.01) {
					return false;
				}

				if (abs($pLocation->yaw - $this->lastPlayerLocation->yaw) > 5 ||
					abs($pLocation->pitch - $this->lastPlayerLocation->pitch) > 5
				) {
					return false;
				}
			} else {
				$this->lastPlayerLocation = $pLocation;
			}

			$this->lastPlayerLocation->yaw = $pLocation->yaw;
			$this->lastPlayerLocation->pitch = $pLocation->pitch;
		}

		return $this->canUse();
	}

	private function shouldFollow(Living $entity) : bool{
		return $entity instanceof Human && (
				$this->items->contains($entity->getInventory()->getItemInHand()) ||
				$this->items->contains($entity->getOffHandInventory()->getItem(0))
			);
	}

	protected function canScare() : bool{
		return $this->canScare;
	}

	public function start() : void{
		$this->lastPlayerLocation = $this->player->getLocation();

		$this->isRunning = true;
	}

	public function stop() : void{
		unset($this->player);
		unset($this->lastPlayerLocation);

		$this->entity->getNavigation()->stop();
		$this->calmDown = $this->reducedTickDelay(100);

		$this->isRunning = false;
	}

	public function tick() : void{
		$this->entity->getLookControl()->setLookAt($this->player, $this->entity->getMaxYawRot() + 20);
		if ($this->entity->getPosition()->distanceSquared($this->player->getPosition()) < 6.25) {
			$this->entity->getNavigation()->stop();
		} else {
			$this->entity->getNavigation()->moveToEntity($this->player, $this->speedModifier);
		}
	}

	public function isRunning() : bool{
		return $this->isRunning;
	}
}
