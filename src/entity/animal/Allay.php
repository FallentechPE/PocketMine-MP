<?php

namespace pocketmine\entity\animal;

use pocketmine\block\Block;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\Jukebox;
use pocketmine\entity\ai\goal\LookAtEntityGoal;
use pocketmine\entity\ai\goal\RandomLookAroundGoal;
use pocketmine\entity\ai\goal\WaterAvoidingRandomStrollGoal;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Location;
use pocketmine\entity\PathfinderMob;
use pocketmine\item\ItemTypeIds;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;
use pocketmine\world\sound\AmethystBlockChimeSound;

class Allay extends PathfinderMob{

	public const TAG_DUPLICATION_COOLDOWN = "AllayDuplicationCooldown";

	protected int $duplicationCooldown;

	protected function initProperties() : void{
		parent::initProperties();

		$this->setMaxHealth(20);
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(0.6, 0.35);
	}

	public static function getNetworkTypeId() : string{
		return EntityIds::ALLAY;
	}

	public function getName() : string{
		return "Allay";
	}

	protected function initEntity(CompoundTag $nbt) : void{
		parent::initEntity($nbt);

		if ($nbt->getLong(self::TAG_DUPLICATION_COOLDOWN) !== null) {
			$this->duplicationCooldown = $nbt->getLong(self::TAG_DUPLICATION_COOLDOWN);
		} else {
			$this->duplicationCooldown = 6000;
		}
	}

	public function saveNBT() : CompoundTag{
		$nbt = parent::saveNBT();

		$nbt->setLong(self::TAG_DUPLICATION_COOLDOWN, $this->duplicationCooldown);

		return $nbt;
	}

	protected function entityBaseTick(int $tickDiff = 1) : bool{
		$hasUpdate = parent::entityBaseTick($tickDiff);

		--$this->duplicationCooldown;

		return $hasUpdate;
	}

	public function onInteract(Player $player, Vector3 $clickPos) : bool{
		$heldItem = $player->getInventory()->getItemInHand();
		if($this->duplicationCooldown <= 0){
			if($heldItem->getTypeId() === ItemTypeIds::AMETHYST_SHARD){
				/** @var Block $block */
				foreach($this->getBlocksInRadius(5.0) as $block){
					if($block->getTypeId() === BlockTypeIds::JUKEBOX && $block instanceof Jukebox){
						if($block->getRecord() !== null){
							$entity = new Allay(Location::fromObject($this->getLocation()->add(0.5, 0.5, 0.5), $this->getWorld()));
							$entity->spawnToAll();
							$this->getWorld()->addSound($this->getPosition(), new AmethystBlockChimeSound());
							$this->duplicationCooldown = 6000;
							$heldItem->pop();
							break;
						}
					}
				}
			}
		}
		return false;
	}

	public function getDefaultMovementSpeed() : float{
		return 0.1;
	}

	protected function registerGoals() : void{
		$this->goalSelector->addGoal(1, new WaterAvoidingRandomStrollGoal($this, 0.8));
		$this->goalSelector->addGoal(2, new LookAtEntityGoal($this, Player::class, 6));
		$this->goalSelector->addGoal(3, new RandomLookAroundGoal($this));
	}
}