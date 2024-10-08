<?php

namespace pocketmine\entity\mob\passive;

use pocketmine\block\Block;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\Jukebox;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Location;
use pocketmine\entity\mob\Mob;
use pocketmine\item\ItemTypeIds;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;
use pocketmine\world\sound\AmethystBlockChimeSound;

class Allay extends Mob{

	private const ALLAY_DUPLICATION_COOLDOWN = "AllayDuplicationCooldown";

	public int $duplicationCooldown = 6000;

	public static function getNetworkTypeId() : string{
		return EntityIds::ALLAY;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(0.6, 0.35);
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(20);
		$this->duplicationCooldown = $nbt->getLong(self::ALLAY_DUPLICATION_COOLDOWN, 6000);
		parent::initEntity($nbt);
	}

	public function saveNBT() : CompoundTag{
		$nbt = parent::saveNBT();
		$nbt->setLong(self::ALLAY_DUPLICATION_COOLDOWN, $this->duplicationCooldown);
		return $nbt;
	}

	public function getName() : string{
		return "Allay";
	}

	protected function entityBaseTick(int $tickDiff = 1) : bool{
		if($this->duplicationCooldown > 0){
			$this->duplicationCooldown = min(0, $this->duplicationCooldown--);
		}

		return parent::entityBaseTick($tickDiff);
	}

	public function onInteract(Player $player, Vector3 $clickPos) : bool{
		$heldItem = $player->getInventory()->getItemInHand();
		if($this->duplicationCooldown === 0){
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
}