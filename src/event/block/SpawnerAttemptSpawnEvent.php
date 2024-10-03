<?php

namespace pocketmine\event\block;

use pocketmine\block\Block;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;

class SpawnerAttemptSpawnEvent extends BlockEvent implements Cancellable{ // todo Wouldn't it be possible to change more values? The size, the number? Is it useful ?
	use CancellableTrait;

	public function __construct(Block $block, private string $entityType){
		parent::__construct($block);
	}

	public function getEntityType() : string{
		return $this->entityType;
	}

	public function setEntityType(string $entityType) : void{
		$this->entityType = $entityType;
	}
}