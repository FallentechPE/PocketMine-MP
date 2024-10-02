<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\event\entity;

use pocketmine\entity\Entity;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\utils\Utils;
use pocketmine\world\Position;

/**
 * @phpstan-extends EntityEvent<Entity>
 */
class EntityTeleportEvent extends EntityEvent implements Cancellable{
	use CancellableTrait;

	public const CAUSE_PLUGIN = 0;
	public const CAUSE_ENDER_PEARL = 1; // projectile is vague and not needed. ender pearl is the only teleporting projectile
	public const CAUSE_CHORUS_FRUIT = 2;
	public const CAUSE_COMMAND = 3;
	public const CAUSE_DIMENSION_CHANGE = 4;
	public const CAUSE_ENTERING_BED = 5; // according to wiki this is teleporting. will not implement for now
	public const CAUSE_MOUNT_ENTITY = 6;
	public const CAUSE_ENTER_ENTITY = 7;
	public const CAUSE_RESPAWN = 8;

	// todo possibly adding entities? such as tamed wolves/cats and endermen

	public function __construct(
		Entity $entity,
		private Position $from,
		private Position $to,
		private int $cause = self::CAUSE_PLUGIN
	){
		$this->entity = $entity;
	}

	public function getFrom() : Position{
		return $this->from;
	}

	public function getTo() : Position{
		return $this->to;
	}

	public function getCause() : int{
		return $this->cause;
	}

	public function setTo(Position $to) : void{
		Utils::checkVector3NotInfOrNaN($to);
		$this->to = $to;
	}
}
