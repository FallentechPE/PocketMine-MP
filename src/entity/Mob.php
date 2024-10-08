<?php

namespace pocketmine\entity;

use pocketmine\block\Block;
use pocketmine\block\Liquid;
use pocketmine\block\VanillaBlocks;
use pocketmine\entity\ai\Brain;
use pocketmine\entity\ai\control\JumpControl;
use pocketmine\entity\ai\control\LookControl;
use pocketmine\entity\ai\control\MoveControl;
use pocketmine\entity\ai\goal\GoalSelector;
use pocketmine\entity\ai\navigation\GroundPathNavigation;
use pocketmine\entity\ai\navigation\PathNavigation;
use pocketmine\entity\ai\sensing\Sensing;
use pocketmine\entity\animation\ArmSwingAnimation;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\pathfinder\BlockPathTypes;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\inventory\CallbackInventoryListener;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\MobInventory;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\MeleeWeaponEnchantment;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\Releasable;
use pocketmine\math\Vector3;
use pocketmine\math\VoxelRayTrace;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataCollection;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataProperties;
use pocketmine\network\mcpe\protocol\types\inventory\ContainerIds;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\timings\Timings;
use pocketmine\utils\AssumptionFailedError;
use pocketmine\utils\Random;
use pocketmine\utils\Utils;
use pocketmine\world\Position;
use pocketmine\world\sound\ItemBreakSound;
use pocketmine\world\sound\MobWarningSound;
use pocketmine\world\World;

abstract class Mob extends Living{

	private const TAG_PERSISTENT = "Persistent"; //TAG_Byte

	protected PathNavigation $navigation;

	protected LookControl $lookControl;

	protected MoveControl $moveControl;

	protected JumpControl $jumpControl;

	protected GoalSelector $goalSelector;

	protected GoalSelector $targetSelector;

	protected Sensing $sensing;

	/** @var array<int, float> BlockPathTypes->id => malus */
	protected array $pathfindingMalus = [];

	protected float $forwardSpeed = 0;

	protected float $upwardSpeed = 0;

	protected float $sidewaysSpeed = 0;

	protected Vector3 $restrictCenter;

	protected float $restrictRadius = -1;

	protected bool $aggressive = false;

	protected bool $isPersistent = false;

	protected Attribute $attackDamageAttr;

	protected Attribute $attackKnockbackAttr;

	protected Attribute $followRangeAttr;

	private const TAG_COMPONENT_GROUPS = "definitions"; //TAG_List

	protected Random $random;

	protected ComponentGroups $componentGroups;

	protected float $stepHeight = 0.6;

	protected float $jumpVelocity = 0.475;

	protected int $noActionTime = 0; //TODO: logic

	protected MobInventory $inventory;

	protected Brain $brain;

	protected ?EntityDamageByEntityEvent $lastDamageByEntity = null;

	protected int $lastDamageByEntityTick = -1; //server tick

	protected bool $hasBeenDamagedByPlayer = false;

	protected function initEntity(CompoundTag $nbt) : void{
		parent::initEntity($nbt);

		$this->initProperties();

		$this->random = new Random();
		$this->inventory = new MobInventory($this);

		if(($componentGroupsTag = $nbt->getTag(self::TAG_COMPONENT_GROUPS)) instanceof ListTag){
			$this->componentGroups = ComponentGroups::fromListTag($componentGroupsTag);
		}else{
			$this->componentGroups = new ComponentGroups();
			if($this->canSaveWithChunk()){
				$this->componentGroups->add(EntityFactory::getInstance()->getSaveId($this::class));
			}
		}
		$syncHeldItem = function() : void{
			foreach($this->getViewers() as $viewer){
				$this->sendHeldItemsPacket($viewer);
			}
		};
		$this->inventory->getListeners()->add(CallbackInventoryListener::onAnyChange(fn() => $syncHeldItem()));
		$inventoryTag = $nbt->getListTag("Inventory");
		if($inventoryTag !== null){
			$inventoryItems = [];
			$armorInventoryItems = [];

			/** @var CompoundTag $item */
			foreach($inventoryTag as $item){
				$slot = $item->getByte("Slot");
				if($slot >= 0 && $slot < $this->inventory->getSize()){ //Inventory
					$inventoryItems[$slot] = Item::nbtDeserialize($item);
				}elseif($slot >= 100 && $slot < 104){ //Armor
					$armorInventoryItems[$slot - 100] = Item::nbtDeserialize($item);
				}
			}

			self::populateInventoryFromListTag($this->inventory, $inventoryItems);
			self::populateInventoryFromListTag($this->armorInventory, $armorInventoryItems);
		}

		if($nbt->count() === 0){ //Entity just created!
			$this->onCreate();
		}

		$this->brain = $this->makeBrain();

		$this->isPersistent = $nbt->getByte(self::TAG_PERSISTENT, 0) !== 0;

		$this->goalSelector = new GoalSelector();
		$this->targetSelector = new GoalSelector();
		$this->lookControl = new LookControl($this);
		$this->moveControl = new MoveControl($this);
		$this->jumpControl = new JumpControl($this);
		$this->navigation = $this->createNavigation();
		$this->sensing = new Sensing($this);

		$this->registerGoals();
	}

	protected function sendSpawnPacket(Player $player) : void{
		parent::sendSpawnPacket($player);
		$this->sendHeldItemsPacket($player);
	}

	protected function sendHeldItemsPacket(Player $player) : void{
		$networksession = $player->getNetworkSession();
		$networksession->sendDataPacket(MobEquipmentPacket::create(
			$this->getId(),
			ItemStackWrapper::legacy($networksession->getTypeConverter()->coreItemStackToNet($this->inventory->getMainHand())),
			$this->inventory->getHeldItemIndex(),
			$this->inventory->getHeldItemIndex(),
			ContainerIds::INVENTORY
		));
		$networksession->sendDataPacket(MobEquipmentPacket::create(
			$this->getId(),
			ItemStackWrapper::legacy($networksession->getTypeConverter()->coreItemStackToNet($this->inventory->getOffHand())),
			0,
			0,
			ContainerIds::OFFHAND
		));
	}

	/**
	 * Called when this entity has just been created and is completely clean.
	 */
	public function onCreate() : void{
		$this->generateEquipment();
	}

	public function generateEquipment() : void{ }

	protected function makeBrain() : Brain{
		return new Brain([], [], []);
	}

	/**
	 * @param Item[]                   $items
	 *
	 * @phpstan-param array<int, Item> $items
	 */
	private static function populateInventoryFromListTag(Inventory $inventory, array $items) : void{
		$listeners = $inventory->getListeners()->toArray();
		$inventory->getListeners()->clear();

		$inventory->setContents($items);

		$inventory->getListeners()->add(...$listeners);
	}

	public function getRandom() : Random{
		return $this->random;
	}

	public function getDefaultMovementSpeed() : float{
		return 1.0;
	}

	public function getMaxUpStep() : float{
		return $this->stepHeight;
	}

	public function getInventory() : MobInventory{
		return $this->inventory;
	}

	public function getBrain() : Brain{
		return $this->brain;
	}

	public function getServer() : Server{
		return $this->location->getWorld()->getServer();
	}

	public function canAttack(Living $target) : bool{
		return true;
	}

	public function isSensitiveToWater() : bool{
		return false;
	}

	public function canSee(Entity $entity) : bool{
		$start = $this->getEyePos();
		$end = $entity->getEyePos();
		$directionVector = $end->subtractVector($start)->normalize();
		if($directionVector->lengthSquared() > 0){
			foreach(VoxelRayTrace::betweenPoints($start, $end) as $vector3){
				$block = $this->getWorld()->getBlockAt((int) $vector3->x, (int) $vector3->y, (int) $vector3->z);

				$blockHitResult = $block->calculateIntercept($start, $end);
				if(!$block->isTransparent() && $blockHitResult !== null){
					return false;
				}
			}
		}
		return true;
	}

	public function jump() : void{
		if($this->onGround || $this->getWorld()->getBlock($this->location) instanceof Liquid){
			$this->motion = $this->motion->withComponents(null, $this->getJumpVelocity(), null);
		}
	}

	public function getJumpVelocity() : float{
		if(!$this->onGround){
			return $this->jumpVelocity;
		}
		return $this->jumpVelocity + ((($jumpBoost = $this->effectManager->get(VanillaEffects::JUMP_BOOST())) !== null ? $jumpBoost->getEffectLevel() : 0) / 10);
	}

	public function isInWater() : bool{
		return $this->getImmersionPercentage(VanillaBlocks::WATER()) > 0;
	}

	public function isInLava() : bool{
		return $this->getImmersionPercentage(VanillaBlocks::LAVA()) > 0;
	}

	/**
	 * Returns the immersion percentage in the specified liquid.
	 *
	 * @return float 0-1
	 */
	public function getImmersionPercentage(Liquid $liquid) : float{
		$entityHeight = $this->getSize()->getHeight();
		$floorX = (int) floor($this->location->x);
		$floorY = (int) floor($this->location->y);
		$floorZ = (int) floor($this->location->z);
		for($y = (int) floor($this->location->y + $entityHeight); $y >= $floorY; $y--){
			$block = $this->getWorld()->getBlockAt($floorX, $y, $floorZ);
			if($block instanceof $liquid){
				$liquidHeigh = ($y + 1) - ($block->getFluidHeightPercent() - 0.1111111);
				return min(1, ($liquidHeigh - $this->location->y) / $entityHeight);
			}
		}
		return 0;
	}

	public function getFluidJumpThreshold() : float{
		return $this->getEyeHeight() < 0.4 ? 0 : 0.4;
	}

	public function canStandOnFluid(Liquid $liquid) : bool{
		return false;
	}

	public function getMaxFallDistance() : int{
		$defaultMax = 3;
		if($this->targetId === null){
			return $defaultMax;
		}

		$maxFallDistance = (int) ($this->getHealth() - $this->getMaxHealth() / 3);
		$maxFallDistance -= (3 - $this->getWorld()->getDifficulty()) * 4;

		return max(0, $maxFallDistance + $defaultMax);
	}

	public function getNoActionTime() : int{
		return $this->noActionTime;
	}

	public function setNoActionTime(int $time) : void{
		$this->noActionTime = $time;
	}

	public function getKnockbackResistance() : float{
		return $this->knockbackResistanceAttr->getValue();
	}

	public function setKnockbackResistance(float $value) : void{
		$this->knockbackResistanceAttr->setValue($value);
	}

	public function saveNBT() : CompoundTag{
		$nbt = parent::saveNBT();

		$nbt->setTag(self::TAG_COMPONENT_GROUPS, $this->componentGroups->toListTag());

		$inventoryTag = new ListTag([], NBT::TAG_Compound);
		$nbt->setTag("Inventory", $inventoryTag);
		if($this->inventory !== null){
			//Normal inventory
			for($slot = 0; $slot < $this->inventory->getSize(); ++$slot){
				$item = $this->inventory->getItem($slot);
				if(!$item->isNull()){
					$inventoryTag->push($item->nbtSerialize($slot));
				}
			}

			//Armor
			for($slot = 100; $slot < 104; ++$slot){
				$item = $this->armorInventory->getItem($slot - 100);
				if(!$item->isNull()){
					$inventoryTag->push($item->nbtSerialize($slot));
				}
			}
		}

		$nbt->setByte(self::TAG_PERSISTENT, $this->isPersistent ? 1 : 0);

		return $nbt;
	}

	public function attack(EntityDamageEvent $source) : void{
		parent::attack($source);

		if(!$source->isCancelled()){
			$this->noActionTime = 0;
		}
	}

	public function setLastDamageCause(EntityDamageEvent $type) : void{
		parent::setLastDamageCause($type);

		if($type instanceof EntityDamageByEntityEvent){
			$this->setLastDamageByEntity($type);

			if($type->getDamager() instanceof Player){
				$this->hasBeenDamagedByPlayer = true;
			}
		}
	}

	public function getLastDamageByEntity() : ?EntityDamageByEntityEvent{
		return $this->lastDamageByEntity;
	}

	public function setLastDamageByEntity(?EntityDamageByEntityEvent $type) : void{
		$this->lastDamageByEntity = $type;
		if($type === null){
			$this->lastDamageByEntityTick = -1;
		}else{
			$this->lastDamageByEntityTick = $this->getWorld()->getServer()->getTick();
		}
	}

	public function getExpirableLastDamageByEntity() : ?EntityDamageByEntityEvent{
		if($this->getWorld()->getServer()->getTick() - $this->lastDamageByEntityTick > 100){
			return null;
		}

		return $this->lastDamageByEntity;
	}

	public function getLastDamageByEntityTick() : int{
		return $this->lastDamageByEntityTick;
	}

	public function hasBeenDamagedByPlayer() : bool{
		return $this->hasBeenDamagedByPlayer;
	}

	protected function shouldDropCookedItems() : bool{
		$deathCause = $this->getLastDamageCause()?->getCause() ?? null;
		return $this->isOnFire() || (!$this->isAlive() && (
					$deathCause === EntityDamageEvent::CAUSE_FIRE ||
					$deathCause === EntityDamageEvent::CAUSE_FIRE_TICK ||
					$deathCause === EntityDamageEvent::CAUSE_LAVA
				));
	}

	protected function checkBlockIntersections() : void{
		$vectors = [];

		foreach($this->getBlocksAroundWithEntityInsideActions() as $block){
			if(!$block->onEntityInside($this) || $this->onInsideBlock($block)){
				$this->blocksAround = null;
			}
			if(($v = $block->addVelocityToEntity($this)) !== null){
				$vectors[] = $v;
			}
		}

		if(count($vectors) > 0){
			$vector = Vector3::sum(...$vectors);
			if($vector->lengthSquared() > 0){
				$d = 0.014;
				$this->motion = $this->motion->addVector($vector->normalize()->multiply($d));
			}
		}
	}

	public function onInsideBlock(Block $block) : bool{
		return false;
	}

	public function getLightLevelDependentMagicValue() : float{
		return Utils::getLightLevelDependentMagicValue(Position::fromObject($this->getEyePos(), $this->location->world));
	}

	protected function initProperties() : void{
		$this->setMovementSpeed($this->getDefaultMovementSpeed());
	}

	protected function registerGoals() : void{
	}

	protected function addAttributes() : void{
		parent::addAttributes();

		$this->attributeMap->add($this->attackDamageAttr = AttributeFactory::getInstance()->mustGet(Attribute::ATTACK_DAMAGE));
		$this->attributeMap->add($this->attackKnockbackAttr = AttributeFactory::getInstance()->mustGet(Attribute::ATTACK_KNOCKBACK));

		$this->followRangeAttr = $this->attributeMap->get(Attribute::FOLLOW_RANGE) ?? throw new AssumptionFailedError("Follow range attribute is null");
	}

	public function createNavigation() : PathNavigation{
		return new GroundPathNavigation($this);
	}

	public function getLookControl() : LookControl{
		return $this->lookControl;
	}

	public function getMoveControl() : MoveControl{
		return $this->moveControl;
	}

	public function getJumpControl() : JumpControl{
		return $this->jumpControl;
	}

	public function getNavigation() : PathNavigation{
		return $this->navigation;
	}

	public function getSensing() : Sensing{
		return $this->sensing;
	}

	public function getMobType() : MobType{
		return MobType::UNDEFINED();
	}

	public function getMobCategory() : MobCategory{
		return MobCategory::CREATURE();
	}

	public function setForwardSpeed(float $forwardSpeed) : void{
		$this->forwardSpeed = $forwardSpeed;
	}

	public function setUpwardSpeed(float $upwardSpeed) : void{
		$this->upwardSpeed = $upwardSpeed;
	}

	public function setSidewaysSpeed(float $sidewaysSpeed) : void{
		$this->sidewaysSpeed = $sidewaysSpeed;
	}

	public function getMaxPitchRot() : int{
		return 40;
	}

	public function getMaxYawRot() : int{
		return 75;
	}

	public function getRotSpeed() : int{
		return 10;
	}

	public function getAmbientSoundInterval() : float{
		return 8;
	}

	public function getAmbientSoundIntervalRange() : float{
		return 16;
	}

	public function getLifeTime() : int{
		return $this->ticksLived;
	}

	/**
	 * Returns maximun time in ticks that this entity can live or -1 if undefined.
	 */
	public function getMaxLifeTime() : int{
		return -1;
	}

	public function getAttackDamage() : float{
		return $this->attackDamageAttr->getValue();
	}

	public function setAttackDamage(float $damage) : void{
		$this->attackDamageAttr->setValue($damage);
	}

	public function getAttackKnockback() : float{
		return $this->attackKnockbackAttr->getValue();
	}

	public function setAttackKnockback(float $kb) : void{
		$this->attackKnockbackAttr->setValue($kb);
	}

	public function getFollowRange() : float{
		return $this->followRangeAttr->getValue();
	}

	public function setFollowRange(float $range) : void{
		$this->followRangeAttr->setValue($range);
	}

	/**
	 * Sets whether this entity is forced to not despawn naturally.
	 *
	 * @return $this
	 */
	public function setPersistent(bool $value = true) : self{
		$this->isPersistent = $value;

		return $this;
	}

	/**
	 * Returns whether this entity is forced to not despawn naturally.
	 */
	public function isPersistent() : bool{
		return $this->isPersistent;
	}

	/**
	 * Returns whether this entity cannot despawn naturally.
	 */
	public function isPersistenceRequired() : bool{
		//TODO: check if is passenger
		return $this->isPersistent() || $this->getNameTag() !== "";
	}

	protected function entityBaseTick(int $tickDiff = 1) : bool{
		Timings::$entityAiTick->startTiming();
		$this->tickAi();
		Timings::$entityAiTick->stopTiming();

		$hasUpdate = parent::entityBaseTick($tickDiff);

		$this->checkDespawn();

		//TODO: leash check

		/*if ($this->ticksLived % 5 === 0) {
			$this->updateControlFlags();
			$hasUpdate = true;
		}*/

		return $hasUpdate;
	}

	public function checkDespawn() : void{
		if($this->getWorld()->getDifficulty() === World::DIFFICULTY_PEACEFUL && $this->shouldDespawnInPeaceful()){
			$this->flagForDespawn();
			return;
		}

		if(!$this->isPersistenceRequired()){
			$maxLifetime = $this->getMaxLifeTime();
			if($maxLifetime !== -1 && $this->ticksLived >= $maxLifetime){
				$this->flagForDespawn();
				return;
			}

			$nearestPlayer = Utils::getNearestPlayer($this);
			if($nearestPlayer !== null){
				$mobCategory = $this->getMobCategory();
				$distanceSquared = $this->location->distanceSquared($nearestPlayer->getPosition());
				if($this->shouldDespawnWhenFarAway($distanceSquared) &&
					$distanceSquared > $mobCategory->getDespawnDistance() ** 2
				){
					$this->flagForDespawn();
				}

				$noDespawnSquared = $mobCategory->getNoDespawnDistance() ** 2;
				if($this->noActionTime > 600 &&
					$this->random->nextBoundedInt(800) === 0 &&
					$distanceSquared > $noDespawnSquared &&
					$this->shouldDespawnWhenFarAway($distanceSquared)
				){
					$this->flagForDespawn();
				}elseif($distanceSquared < $noDespawnSquared){
					$this->noActionTime = 0;
				}
			}
		}else{
			$this->noActionTime = 0;
		}
	}

	public function shouldDespawnInPeaceful() : bool{
		return false;
	}

	public function shouldDespawnWhenFarAway(float $distanceSquared) : bool{
		return true;
	}

	public function tickAi() : void{
		$this->noActionTime++;

		$this->sensing->tick();

		Timings::$goalSelector->startTiming();

		$n = $this->ticksLived + $this->getId();
		if($n % 2 !== 0 && !$this->justCreated){
			$this->targetSelector->tickRunningGoals();
			$this->goalSelector->tickRunningGoals();
		}else{
			$this->targetSelector->tick();
			$this->goalSelector->tick();
		}

		Timings::$goalSelector->stopTiming();

		Timings::$navigation->startTiming();
		$this->navigation->tick();
		Timings::$navigation->stopTiming();

		$this->moveControl->tick();
		$this->lookControl->tick();
		$this->jumpControl->tick();

		// Movement update
		$this->sidewaysSpeed *= 0.98;
		$this->forwardSpeed *= 0.98;
		$this->travel(new Vector3($this->sidewaysSpeed, $this->upwardSpeed, $this->forwardSpeed));
	}

	public function travel(Vector3 $movementInput) : void{
		// TODO: More complex movement suff :P
		$motion = Utils::movementInputToMotion($movementInput, $this->location->yaw, $this->getMovementSpeed());

		//Climb stuff
		if($this->isCollidedHorizontally && $this->onClimbable()){
			$motion->y = 0.2 - $this->motion->y;
		}

		$this->addMotion($motion->x, $motion->y, $motion->z);
	}

	protected function onClimbable() : bool{
		return false;
	}

	protected function updateControlFlags() : void{
		// TODO!
	}

	public function getPathfindingMalus(BlockPathTypes $pathType) : float{
		// TODO: vehicle checks
		return $this->pathfindingMalus[$pathType->id()] ?? $pathType->getMalus();
	}

	public function setPathfindingMalus(BlockPathTypes $pathType, float $malus) : void{
		$this->pathfindingMalus[$pathType->id()] = $malus;
	}

	public function canUseReleasable(Releasable $item) : bool{
		return false;
	}

	public function isWithinRestriction(?Vector3 $pos = null) : bool{
		$pos = $pos ?? $this->location;
		if($this->restrictRadius === -1.0){
			return true;
		}
		return $this->restrictCenter->distanceSquared($pos) < ($this->restrictRadius ** 2);
	}

	public function restrictTo(Vector3 $center, float $radius) : void{
		$this->restrictCenter = $center;
		$this->restrictRadius = $radius;
	}

	public function getRestrictCenter() : Vector3{
		if(!isset($this->restrictCenter)){
			$this->restrictCenter = Vector3::zero();
		}
		return $this->restrictCenter;
	}

	public function getRestrictRadius() : float{
		return $this->restrictRadius;
	}

	public function clearRestriction() : void{
		$this->restrictRadius = -1;
	}

	public function hasRestriction() : bool{
		return $this->restrictRadius !== -1.0;
	}

	public function isAggressive() : bool{
		return $this->aggressive;
	}

	public function setAggressive(bool $aggressive = true) : void{
		$this->aggressive = $aggressive;
	}

	public function getPerceivedDistanceSqrForMeleeAttack(Entity $target) : float{
		//TODO: Camels Y extra!
		return $this->location->distanceSquared($target->getPosition());
	}

	protected function doAttackAnimation() : void{
		$this->broadcastAnimation(new ArmSwingAnimation($this));
	}

	/**
	 * Attacks the given entity with the currently-held item.
	 * TODO: make a PR that implements this un PM core.
	 *
	 * @return bool if the entity was dealt damage
	 */
	public function attackEntity(Entity $entity) : bool{
		if(!$entity->isAlive()){
			return false;
		}

		$heldItem = $this->inventory->getItemInHand();
		$oldItem = clone $heldItem;

		$itemAttackPoints = $heldItem->getAttackPoints();
		$attackPoints = $this->getAttackDamage();
		$baseDamage = $itemAttackPoints <= 1 ? $attackPoints : $attackPoints + $itemAttackPoints;

		$ev = new EntityDamageByEntityEvent($this, $entity, EntityDamageEvent::CAUSE_ENTITY_ATTACK, $baseDamage);
		$ev->setKnockBack($this->getAttackKnockback());

		$meleeEnchantmentDamage = 0;
		/** @var EnchantmentInstance[] $meleeEnchantments */
		$meleeEnchantments = [];
		foreach($heldItem->getEnchantments() as $enchantment){
			$type = $enchantment->getType();
			if($type instanceof MeleeWeaponEnchantment && $type->isApplicableTo($entity)){
				$meleeEnchantmentDamage += $type->getDamageBonus($enchantment->getLevel());
				$meleeEnchantments[] = $enchantment;
			}
		}
		$ev->setModifier($meleeEnchantmentDamage, EntityDamageEvent::MODIFIER_WEAPON_ENCHANTMENTS);

		$entity->attack($ev);

		$this->doAttackAnimation();

		if($this->isOnFire()){
			$entity->setOnFire(8);
		}

		foreach($meleeEnchantments as $enchantment){
			$type = $enchantment->getType();
			assert($type instanceof MeleeWeaponEnchantment);
			$type->onPostAttack($this, $entity, $enchantment->getLevel());
		}

		if($this->isAlive()){
			//reactive damage like thorns might cause us to be killed by attacking another mob, which
			//would mean we'd already have dropped the inventory by the time we reached here

			$returnedItems = []; //TODO: do something with returned items
			if($heldItem->onAttackEntity($entity, $returnedItems) && $oldItem->equalsExact($this->inventory->getItemInHand())){
				if($heldItem instanceof Durable && $heldItem->isBroken()){
					$this->broadcastSound(new ItemBreakSound());
				}
				$this->inventory->setItemInHand($heldItem);
			}
		}

		return true;
	}

	public function performRangedAttack(Entity $target, float $force) : void{
	}

	public function canStandAt(Vector3 $pos) : bool{
		$world = $this->getWorld();

		$below = $world->getBlock($pos->down());
		if(!$below->isSolid()){
			return false;
		}

		$diff = $this->location->subtractVector($pos);
		return count($world->getCollisionBlocks($this->boundingBox->addCoord($diff->x, $diff->y, $diff->z), true)) === 0;
	}

	public function onRandomTeleport(Vector3 $from, Vector3 $to) : void{
	}

	public function setTargetEntity(?Entity $target) : void{
		if($target !== null && $target->getId() !== $this->targetId){
			$this->broadcastSound(new MobWarningSound($this));
		}

		parent::setTargetEntity($target);
	}

	public function teleport(Vector3 $pos, ?float $yaw = null, ?float $pitch = null, int $cause = EntityTeleportEvent::CAUSE_PLUGIN) : bool{
		if(parent::teleport($pos, $yaw, $pitch)){
			$this->navigation->stop();

			return true;
		}

		return false;
	}

	public function getEquipmentDropProbability() : float{
		return 0.25;
	}

	/**
	 * @return Item[]
	 */
	public function getEquipmentDrops() : array{
		$drops = [];

		$dropChance = $this->getEquipmentDropProbability();
		foreach([$this->inventory, $this->armorInventory] as $inventory){
			foreach($inventory->getContents() as $item){
				if(!$item->hasEnchantment(VanillaEnchantments::VANISHING()) && lcg_value() <= $dropChance){
					if($item instanceof Durable){
						$maxDurability = $item->getMaxDurability();
						$item->setDamage($maxDurability - $this->random->nextBoundedInt(1 + $this->random->nextBoundedInt(max($maxDurability - 3, 1))));
					}
					$drops[] = $item;
				}
			}
		}

		return $drops;
	}

	public function getDrops() : array{
		return $this->getEquipmentDrops();
	}

	public function onEat() : void{
	}

	public function onLightningBoltHit() : bool{
		return false;
	}

	protected function syncNetworkData(EntityMetadataCollection $properties) : void{
		parent::syncNetworkData($properties);

		$properties->setFloat(EntityMetadataProperties::AMBIENT_SOUND_INTERVAL_MIN, $this->getAmbientSoundInterval());
		$properties->setFloat(EntityMetadataProperties::AMBIENT_SOUND_INTERVAL_RANGE, $this->getAmbientSoundIntervalRange());
		$properties->setString(EntityMetadataProperties::AMBIENT_SOUND_EVENT, "ambient");
	}
}