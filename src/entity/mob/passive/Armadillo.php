<?php

namespace pocketmine\entity\mob\passive;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\mob\Ageable;
use pocketmine\entity\mob\AgeableTrait;
use pocketmine\entity\mob\Mob;
use pocketmine\item\Brush;
use pocketmine\item\ItemTypeIds;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;
use pocketmine\world\sound\ArmadilloBrushSound;
use pocketmine\world\sound\ArmadilloScuteDropSound;

class Armadillo extends Mob implements Ageable{
	use AgeableTrait;

	public const MIN_DROP_TIME = 6000;
	public const MAX_DROP_TIME = 12000;

	public const IS_ROLLED_UP = "is_rolled_up"; //TAG_Byte
	public const IS_THREATENED = "is_threatened"; //TAG_Byte
	public const IS_TRYING_TO_RELAX = "is_trying_to_relax"; //TAG_Byte

	public bool $rolledUp = false;
	public bool $threatened = false;
	public bool $tryingToRelax = false;
	public int $scuteDropCooldown;

	public static function getNetworkTypeId() : string{
		return EntityIds::ARMADILLO;
	}

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(0.65, 0.7);
	}

	public function saveNBT() : CompoundTag{
		$nbt = parent::saveNBT();
		$nbt->setByte(self::IS_ROLLED_UP, $this->rolledUp);
		$nbt->setByte(self::IS_THREATENED, $this->threatened);
		$nbt->setByte(self::IS_TRYING_TO_RELAX, $this->tryingToRelax);
		return $this->saveAgeableNBT($nbt);
	}

	protected function entityBaseTick(int $tickDiff = 1) : bool{
		parent::entityBaseTick($tickDiff);

		if ($this->scuteDropCooldown > 0) {
			$this->scuteDropCooldown -= $tickDiff;
		}

		if ($this->scuteDropCooldown <= 0) {
			$this->getWorld()->dropItem($this->getPosition()->add(0.5, 0.85, 0.5), VanillaItems::SCUTE());
			$this->getWorld()->addSound($this->getPosition(), new ArmadilloScuteDropSound());

			$this->scuteDropCooldown = mt_rand(self::MIN_DROP_TIME, self::MAX_DROP_TIME);
		}

		return $this->doAgeableTick($tickDiff);
	}

	public function onInteract(Player $player, Vector3 $clickPos) : bool{
		$heldItem = $player->getInventory()->getItemInHand();
		if($heldItem->getTypeId() === ItemTypeIds::BRUSH && $heldItem instanceof Brush){
			$heldItem->applyDamage(16); // wiki says 5 times max 65/4
			$this->getWorld()->dropItem($this->getPosition()->add(0.5, 0.85, 0.5), VanillaItems::SCUTE());
			$this->getWorld()->addSound($this->getPosition(), new ArmadilloBrushSound());
		}

		return false;
	}

	public function getXpDropAmount() : int{
		if(!$this->isBaby()){
			return mt_rand(1, 3);
		}
		return 0;
	}

	public function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(12);
		$this->setMovementSpeed(0.14);
		$this->scuteDropCooldown = mt_rand(self::MIN_DROP_TIME, self::MAX_DROP_TIME);
		parent::initEntity($nbt);
	}

	public function getName() : string{
		return "Armadillo";
	}
}