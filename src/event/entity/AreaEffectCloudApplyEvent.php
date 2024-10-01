<?php

namespace pocketmine\event\entity;

use pocketmine\entity\Living;
use pocketmine\entity\object\AreaEffectCloud;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;

/**
 * Called when a area effect cloud applies it's effects. Happens once
 * every {@link AreaEffectCloud::getWaiting()} is reached and there are affected entities.
 *
 * @phpstan-extends EntityEvent<AreaEffectCloud>
 */
class AreaEffectCloudApplyEvent extends EntityEvent implements Cancellable{
	use CancellableTrait;

	/**
	 * @param Living[] $affectedEntities
	 */
	public function __construct(
		AreaEffectCloud $entity,
		protected array $affectedEntities
	){
		$this->entity = $entity;
	}

	/**
	 * @return AreaEffectCloud
	 */
	public function getEntity(){
		return $this->entity;
	}

	/**
	 * Returns the affected entities.
	 *
	 * @return Living[]
	 */
	public function getAffectedEntities() : array{
		return $this->affectedEntities;
	}
}