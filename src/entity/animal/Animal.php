<?php

namespace pocketmine\entity\animal;

use pocketmine\block\BlockTypeIds;
use pocketmine\entity\AgeableMob;
use pocketmine\entity\animation\BabyAnimalFeedAnimation;
use pocketmine\entity\animation\BreedingAnimation;
use pocketmine\entity\animation\ConsumingItemAnimation;
use pocketmine\entity\pathfinder\BlockPathTypes;
use pocketmine\item\Item;
use pocketmine\item\ItemTypeIds;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataCollection;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataFlags;
use pocketmine\player\Player;
use pocketmine\utils\Binary;
use pocketmine\utils\Utils;
use function mt_rand;

abstract class Animal extends AgeableMob {

	private const TAG_IN_LOVE_TICKS = "InLove"; //TAG_Int

	protected const PARENT_AGE_AFTER_BREEDING = 6000;

	private int $inLoveTicks = 0;
	private ?Player $loveCauser = null;

	protected function initProperties() : void{
		parent::initProperties();

		$this->setPathfindingMalus(BlockPathTypes::DANGER_FIRE(), 16);
		$this->setPathfindingMalus(BlockPathTypes::DAMAGE_FIRE(), -1);
	}

	protected function initEntity(CompoundTag $nbt) : void{
		parent::initEntity($nbt);

		$this->inLoveTicks = $nbt->getInt(self::TAG_IN_LOVE_TICKS, 0);
	}

	public function saveNBT() : CompoundTag{
		$nbt = parent::saveNBT();

		$nbt->setInt(self::TAG_IN_LOVE_TICKS, Binary::signInt($this->inLoveTicks));

		return $nbt;
	}

	protected function syncNetworkData(EntityMetadataCollection $properties) : void{
		parent::syncNetworkData($properties);
		$properties->setGenericFlag(EntityMetadataFlags::INLOVE, $this->inLoveTicks > 0);
	}

	public function tickAi() : void{
		parent::tickAi();

		if ($this->getAge() !== AgeableMob::ADULT_AGE) {
			$this->inLoveTicks = 0;
		}

		if ($this->inLoveTicks > 0) {
			$this->inLoveTicks--;

			if ($this->inLoveTicks % 16 === 0) {
				$this->broadcastAnimation(new BreedingAnimation($this));
			}
		}
	}

	public function getWalkTargetValue(Vector3 $position) : float{
		return $this->getWorld()->getBlock($position)->getTypeId() === BlockTypeIds::GRASS ? 10 : 0;
		//TODO: If it is not grass calculate the value using light level
	}

	//TODO: natural spawning logic

	public function getAmbientSoundInterval() : float{
		return 12;
	}

	public function getXpDropAmount() : int{
		if (!$this->isBaby() && $this->hasBeenDamagedByPlayer()) {
			return mt_rand(1, 3);
		}

		return 0;
	}

	public function isFood(Item $item) : bool {
		return $item->getTypeId() === ItemTypeIds::WHEAT;
	}

	public function onInteract(Player $player, Vector3 $clickPos) : bool{
		$item = $player->getInventory()->getItemInHand();
		if ($this->isFood($item)) {
			$age = $this->getAge();
			if ($age === AgeableMob::ADULT_AGE && $this->canFallInLove()) {
				Utils::popItemInHand($player);
				$this->setInLove($player);
				$this->setPersistent();

				$this->broadcastAnimation(new ConsumingItemAnimation($this, $item));

				return true;
			}

			if ($this->isBaby()) {
				Utils::popItemInHand($player);
				$this->ageUp(static::getAgeUpWhenFeeding($age));
				$this->setPersistent();

				$this->broadcastAnimation(new BabyAnimalFeedAnimation($this));

				return true;
			}
		}

		return parent::onInteract($player, $clickPos);
	}

	public function canFallInLove() : bool {
		return $this->inLoveTicks <= 0;
	}

	public function setInLove(?Player $player = null) : void {
		$this->inLoveTicks = 600;
		$this->loveCauser = $player;

		$this->networkPropertiesDirty = true;
	}

	public function isInLove() : bool{
		return $this->inLoveTicks > 0;
	}

	public function setInLoveTicks(int $ticks) : void{
		$inLove = $this->isInLove();
		if ($inLove && $ticks <= 0 || !$inLove && $ticks > 0) {
			$this->networkPropertiesDirty = true;
		}

		$this->inLoveTicks = $ticks;
	}

	public function getInLoveTicks() : int {
		return $this->inLoveTicks;
	}

	public function getLoveCauser() : ?Player {
		return $this->loveCauser;
	}

	public function canMate(Animal $other) : bool{
		if ($other === $this) {
			return false;
		}
		if ($other::class !== $this::class) {
			return false;
		}

		return $this->isInLove() && $other->isInLove();
	}

	public function spawnChildFromBreeding(Animal $partner) : void{
		$offspring = $this->getBreedOffspring($partner);
		if ($offspring !== null) {
			$offspring->setBaby();
			$offspring->setPersistent();
			$offspring->spawnToAll();

			$this->finalizeSpawnChildFromBreeding($partner, $offspring);
		}
	}

	public function finalizeSpawnChildFromBreeding(Animal $partner, AgeableMob $offspring) : void{
		foreach ([$this, $partner] as $parent) {
			$parent->setAge(self::PARENT_AGE_AFTER_BREEDING);
			$parent->setInLoveTicks(0);
		}

		$this->getWorld()->dropExperience($this->location, $this->random->nextBoundedInt(7) + 1);
	}
}
