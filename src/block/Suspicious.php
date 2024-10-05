<?php

namespace pocketmine\block;

use pocketmine\block\utils\Fallable;
use pocketmine\block\utils\FallableTrait;
use pocketmine\data\runtime\RuntimeDataDescriber;

class Suspicious extends Opaque implements Fallable{
	use FallableTrait;

	public const MIN_PROGRESS = 0;
	public const MAX_PROGRESS = 3;

	protected int $progress = self::MIN_PROGRESS;
	protected bool $hanging = true;

	protected function describeBlockOnlyState(RuntimeDataDescriber $w) : void{
		$w->boundedIntAuto(self::MIN_PROGRESS, self::MAX_PROGRESS, $this->progress);
		$w->bool($this->hanging);
	}

	public function isHanging() : bool{
		return $this->hanging;
	}

	/** @return $this */
	public function setHanging(bool $hanging) : self {
		$this->hanging = $hanging;
		return $this;
	}

	public function getProgress() : int{
		return $this->progress;
	}

	/** @return $this */
	public function setProgress(int $progress) : self	{
		$this->progress = $progress;
		return $this;
	}

}