<?php

namespace pocketmine\entity\ai\goal\enderman;

use pocketmine\entity\ai\goal\target\TargetGoal;
use pocketmine\entity\ai\targeting\TargetingConditions;
use pocketmine\entity\Entity;
use pocketmine\entity\monster\Enderman;
use pocketmine\math\AxisAlignedBB;
use pocketmine\player\Player;
use function array_reduce;

class LookForStaringPlayerGoal extends TargetGoal {

	protected int $aggroCooldownTicks = 0;

	protected TargetingConditions $startAggroTargetConditions;

	public function __construct(
		protected Enderman $enderman,
		?TargetingConditions $startAggroTargetConditions = null
	) {
		parent::__construct($enderman, false);

		$this->startAggroTargetConditions = $startAggroTargetConditions ?? (new TargetingConditions())
			->allowUnseeable()
			->testInvisible(false)
			->setRange($this->getFollowDistance());
	}

	public function canUse() : bool{
		if ($this->aggroCooldownTicks > 0) {
			$this->aggroCooldownTicks--;
			return false;
		}

		$this->findTarget();
		return $this->target !== null;
	}

	protected function findTarget() : void{
		$pos = $this->enderman->getLocation();
		$this->target = array_reduce($this->enderman->getWorld()->getCollidingEntities($this->getTargetSearchArea($this->getFollowDistance()), $this->enderman),
			function(?Player $carry, Entity $current) use ($pos) : ?Player {
				if (!$current instanceof Player ||
					!$this->isAngerTriggering($current) ||
					!$this->canAttack($current, $this->startAggroTargetConditions)
				) {
					return $carry;
				}

				return ($carry !== null &&
					$carry->getPosition()->distanceSquared($pos) < $current->getPosition()->distanceSquared($pos)
				) ? $carry : $current;
			});
	}

	public function getTargetSearchArea(float $range) : AxisAlignedBB{
		return $this->enderman->getBoundingBox()->expandedCopy($range, $range, $range);
	}

	public function isAngerTriggering(Player $player) : bool{
		return $this->enderman->isLookingAtMe($player);
	}

	public function start() : void{
		$this->aggroCooldownTicks = $this->adjustedTickDelay(100);
		$this->enderman->setTargetEntity($this->target);
		$this->enderman->onBeingStaredAt();

		parent::start();
	}
}
