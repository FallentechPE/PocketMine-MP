<?php

namespace pocketmine\entity\monster;

use pocketmine\entity\ai\goal\FloatGoal;
use pocketmine\entity\ai\goal\LookAtEntityGoal;
use pocketmine\entity\ai\goal\MeleeAttackGoal;
use pocketmine\entity\ai\goal\RandomLookAroundGoal;
use pocketmine\entity\ai\goal\target\HurtByTargetGoal;
use pocketmine\entity\ai\goal\target\NearestAttackableGoal;
use pocketmine\entity\ai\goal\WaterAvoidingRandomStrollGoal;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Living;
use pocketmine\entity\MobType;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;

class Endermite extends Monster {

	private const TAG_LIFE = "Lifetime";

	public const MAX_LIFE = 2400;

	public static function getNetworkTypeId() : string{ return EntityIds::ENDERMITE; }

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(0.3, 0.4);
	}

	public function getName() : string{
		return "Endermite";
	}

	public function getMobType() : MobType{
		return MobType::ARTHROPOD();
	}

	protected function registerGoals() : void{
		$this->goalSelector->addGoal(1, new FloatGoal($this));
		//TODO: powder snow climb goal
		$this->goalSelector->addGoal(2, new MeleeAttackGoal($this, 1, false));
		$this->goalSelector->addGoal(3, new WaterAvoidingRandomStrollGoal($this, 1));
		$this->goalSelector->addGoal(7, new LookAtEntityGoal($this, Player::class, 8));
		$this->goalSelector->addGoal(8, new RandomLookAroundGoal($this));

		$this->targetSelector->addGoal(1, (new HurtByTargetGoal($this))->setAlertOthers());
		$this->targetSelector->addGoal(2, new NearestAttackableGoal($this, Living::class, NearestAttackableGoal::DEFAULT_RANDOM_INTERVAL, true, false, fn(Living $e) => $e instanceof Player || $e instanceof Enderman));
	}

	protected function initEntity(CompoundTag $nbt) : void{
		$this->ticksLived = $nbt->getInt(self::TAG_LIFE, 0);

		parent::initEntity($nbt);
	}

	public function saveNBT() : CompoundTag{
		$nbt = parent::saveNBT();

		$nbt->setInt(self::TAG_LIFE, $this->ticksLived);

		return $nbt;
	}

	protected function initProperties() : void{
		parent::initProperties();

		$this->setMaxHealth(8);
		$this->setAttackDamage(2);
	}

	public function getDefaultMovementSpeed() : float{
		return 0.25;
	}

	public function getMaxLifeTime() : int{
		return self::MAX_LIFE;
	}

	public function getXpDropAmount() : int{
		if ($this->hasBeenDamagedByPlayer()) {
			return 3;
		}

		return 0;
	}

	//TODO: spawn rules code
}
