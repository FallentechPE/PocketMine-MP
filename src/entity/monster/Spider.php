<?php

namespace pocketmine\entity\monster;

use pocketmine\entity\ai\goal\FloatGoal;
use pocketmine\entity\ai\goal\LeapAtTargetGoal;
use pocketmine\entity\ai\goal\LookAtEntityGoal;
use pocketmine\entity\ai\goal\MeleeAttackGoal;
use pocketmine\entity\ai\goal\RandomLookAroundGoal;
use pocketmine\entity\ai\goal\target\HurtByTargetGoal;
use pocketmine\entity\ai\goal\target\NearestAttackableGoal;
use pocketmine\entity\ai\goal\WaterAvoidingRandomStrollGoal;
use pocketmine\entity\ai\navigation\WallClimberNavigation;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\MobType;
use pocketmine\item\VanillaItems;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataCollection;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataFlags;
use pocketmine\player\Player;
use function mt_rand;

class Spider extends Monster {

	public static function getNetworkTypeId() : string{ return EntityIds::SPIDER; }

	protected bool $isClimbing = false;

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(0.9, 1.4, 0.65);
	}

	public function getName() : string{
		return "Spider";
	}

	public function getMobType() : MobType{
		return MobType::ARTHROPOD();
	}

	protected function registerGoals() : void{
		$this->goalSelector->addGoal(1, new FloatGoal($this));
		$this->goalSelector->addGoal(3, new LeapAtTargetGoal($this, 0.4));

		$this->goalSelector->addGoal(4, new class($this) extends MeleeAttackGoal {
			public function __construct(Spider $mob) {
				parent::__construct($mob, 1, true);
			}

			public function canUse() : bool{
				return parent::canUse(); //TODO: check there is no passenger controlling it
			}

			public function canContinueToUse() : bool{
				$lightMagicValue = $this->mob->getLightLevelDependentMagicValue();
				if ($lightMagicValue >= 0.5 && $this->mob->getRandom()->nextBoundedInt(100) === 0) {
					$this->mob->setTargetEntity(null);
					return false;
				}

				return parent::canContinueToUse();
			}
		});

		$this->goalSelector->addGoal(5, new WaterAvoidingRandomStrollGoal($this, 0.8));
		$this->goalSelector->addGoal(7, new LookAtEntityGoal($this, Player::class, 8));
		$this->goalSelector->addGoal(8, new RandomLookAroundGoal($this));

		$this->targetSelector->addGoal(1, new HurtByTargetGoal($this));

		$this->targetSelector->addGoal(2, new class($this) extends NearestAttackableGoal {
			public function __construct(Spider $mob) {
				parent::__construct(entity: $mob, targetType: Player::class); //TODO: attack golems too!
			}

			public function canUse() : bool{
				return $this->entity->getLightLevelDependentMagicValue() >= 0.5 ? false : parent::canUse();
			}
		});
	}

	protected function initProperties() : void{
		parent::initProperties();

		$this->setMaxHealth(16);
		$this->setAttackDamage(2);
	}

	public function getDefaultMovementSpeed() : float{
		return 0.3;
	}

	public function createNavigation() : WallClimberNavigation{
		return new WallClimberNavigation($this);
	}

	public function getXpDropAmount() : int{
		return $this->hasBeenDamagedByPlayer() ? 5 : 0;
	}

	public function getDrops() : array{
		$drops = parent::getDrops();
		$drops[] = VanillaItems::STRING()->setCount(mt_rand(0, 2)); //TODO: looting...

		if ($this->hasBeenDamagedByPlayer()) {
			$drops[] = VanillaItems::SPIDER_EYE()->setCount(mt_rand(0, 1)); //TODO: looting...
		}

		return $drops;
	}

	protected function entityBaseTick(int $tickDiff = 1) : bool{
		$hasUpdate = parent::entityBaseTick($tickDiff);

		if ($this->isCollidedHorizontally !== $this->isClimbing) {
			$this->setClimbing($this->isCollidedHorizontally);

			$hasUpdate = true;
		}

		return $hasUpdate;
	}

	public function isClimbing() : bool{
		return $this->isClimbing;
	}

	public function setClimbing(bool $value) : void{
		$this->isClimbing = $value;

		$this->networkPropertiesDirty = true;
	}

	protected function onClimbable() : bool{
		return $this->isClimbing;
	}

	protected function syncNetworkData(EntityMetadataCollection $properties) : void{
		parent::syncNetworkData($properties);

		$properties->setGenericFlag(EntityMetadataFlags::WALLCLIMBING, $this->isClimbing);
		$properties->setGenericFlag(EntityMetadataFlags::CAN_CLIMB, true);
	}

	//TODO: riding and jokey stuff!

	//TODO: spawn rules code
}
