<?php

namespace pocketmine\entity\ai\goal;

use InvalidArgumentException;
use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\entity\animal\Animal;
use pocketmine\entity\animation\EatBlockAnimation;
use pocketmine\world\particle\BlockBreakParticle;
use function max;

class EatBlockGoal extends Goal {

	public const EAT_ANIMATION_TICKS = 40;

	/** @var array<int, Block> */
	private static array $eatableBlocks;

	/** @var array<int, Block> */
	private static array $eatableBlockReplacers;

	private static function initEatableBlocks() : void{
		if (!isset(self::$eatableBlocks)) {
			self::addEatableBlock(VanillaBlocks::GRASS(), VanillaBlocks::DIRT());
			self::addEatableBlock(VanillaBlocks::TALL_GRASS(), VanillaBlocks::AIR());
			self::addEatableBlock(VanillaBlocks::FERN(), VanillaBlocks::AIR());
		}
	}

	public static function addEatableBlock(Block $block, Block $replacer) : void{
		$id = $block->getStateId();
		self::$eatableBlocks[$id] = $block;
		self::$eatableBlockReplacers[$id] = $replacer;
	}

	public static function isEatable(Block $block) : bool{
		self::initEatableBlocks();

		return isset(self::$eatableBlocks[$block->getStateId()]);
	}

	public static function getEatableReplacer(Block $block) : Block{
		self::initEatableBlocks();

		if (!self::isEatable($block)) {
			throw new InvalidArgumentException("Block provided is not eatable");
		}

		return clone self::$eatableBlockReplacers[$block->getStateId()];
	}

	private int $eatAnimationTick = 0;

	public function __construct(
		protected Animal $entity
	) {
		$this->setFlags(Goal::FLAG_MOVE, Goal::FLAG_LOOK, Goal::FLAG_JUMP);
	}

	public function canUse() : bool{
		if ($this->entity->getRandom()->nextBoundedInt($this->entity->isBaby() ? 50 : 1000) !== 0) {
			return false;
		}

		return $this->findEatableBlock() !== null;
	}

	public function start() : void{
		$this->eatAnimationTick = $this->adjustedTickDelay(self::EAT_ANIMATION_TICKS);
		$this->entity->getNavigation()->stop();

		$this->entity->broadcastAnimation(new EatBlockAnimation($this->entity));
	}

	public function stop() : void{
		$this->eatAnimationTick = 0;
	}

	public function canContinueToUse() : bool{
		return $this->eatAnimationTick > 0;
	}

	public function getEatAnimationTick() : int{
		return $this->eatAnimationTick;
	}

	public function tick() : void{
		$this->eatAnimationTick = max($this->eatAnimationTick - 1, 0);
		if ($this->eatAnimationTick === $this->adjustedTickDelay(4)) {
			$block = $this->findEatableBlock();
			if ($block !== null) {
				$world = $this->entity->getWorld();
				$world->addParticle($this->entity->getPosition()->floor()->add(0.5, 0.5, 0.5), new BlockBreakParticle($block));

				$world->setBlock($block->getPosition(), self::getEatableReplacer($block));

				$this->entity->onEat();
			}
		}
	}

	public function findEatableBlock() : ?Block{
		$world = $this->entity->getWorld();
		$pos = $this->entity->getPosition();
		foreach ([$pos, $pos->down()] as $position) {
			$block = $world->getBlock($position);
			if (self::isEatable($block)) {
				return $block;
			}
		}

		return null;
	}
}
