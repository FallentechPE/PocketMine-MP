<?php

namespace pocketmine\item;

use pocketmine\block\Block;
use pocketmine\block\PowderSnow;
use pocketmine\event\player\PlayerBucketEmptyEvent;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class BlockBucket extends Item{ // todo By the same logic, lava and water should also be BlockBuckets. I don't think trying to generalize buckets is correct in this PR.

	private Block $block;

	public function __construct(ItemIdentifier $identifier, string $name, Block $block){
		parent::__construct($identifier, $name);
		$this->block = $block;
	}

	public function getMaxStackSize() : int{
		return 1;
	}

	public function getFuelTime() : int{
		return 0;
	}

	public function getFuelResidue() : Item{
		return VanillaItems::BUCKET();
	}

	public function onInteractBlock(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, array &$returnedItems) : ItemUseResult{
		if(!$blockReplace->canBeReplaced()){
			return ItemUseResult::NONE();
		}

		//TODO: move this to generic placement logic
		$resultBlock = clone $this->block;

		if(!$resultBlock instanceof PowderSnow){ // Why name the BlockBucket class in this case and only accept snow, I think this class could just be renamed
			return ItemUseResult::FAIL();
		}

		$ev = new PlayerBucketEmptyEvent($player, $blockReplace, $face, $this, VanillaItems::BUCKET());
		$ev->call();
		if(!$ev->isCancelled()){
			$player->getWorld()->setBlock($blockReplace->getPosition(), $resultBlock);
			$player->getWorld()->addSound($blockReplace->getPosition()->add(0.5, 0.5, 0.5), $resultBlock->getBucketEmptySound());

			$this->pop();
			$returnedItems[] = $ev->getItem();
			return ItemUseResult::SUCCESS();
		}

		return ItemUseResult::FAIL();
	}

}