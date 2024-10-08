<?php

namespace pocketmine\entity\schedule;

use pocketmine\utils\EnumTrait;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static Activity AVOID()
 * @method static Activity CELEBRATE()
 * @method static Activity CORE()
 * @method static Activity FIGHT()
 * @method static Activity HIDE()
 * @method static Activity IDLE()
 * @method static Activity MEET()
 * @method static Activity PANIC()
 * @method static Activity PLAY()
 * @method static Activity PRE_RAID()
 * @method static Activity RAID()
 * @method static Activity REST()
 * @method static Activity RIDE()
 * @method static Activity WORK()
 */
final class Activity{
	use EnumTrait;

	protected static function setup() : void{
		self::registerAll(
			new self("core"),
			new self("idle"),
			new self("work"),
			new self("play"),
			new self("rest"),
			new self("meet"),
			new self("panic"),
			new self("raid"),
			new self("pre_raid"),
			new self("hide"),
			new self("fight"),
			new self("celebrate"),
			new self("avoid"),
			new self("ride")
		);
	}

	public function getName() : string{
		return $this->name();
	}
}
