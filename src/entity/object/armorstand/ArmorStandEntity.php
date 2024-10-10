<?php

namespace pocketmine\entity\object\armorstand;

use pocketmine\entity\EntityFactory;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Living;
use pocketmine\entity\Location;
use pocketmine\entity\object\armorstand\behavior\ArmorStandBehaviorRegistry;
use pocketmine\entity\projectile\Arrow;
use pocketmine\event\entity\ArmorStandMoveEvent;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerChangeArmorStandPoseEvent;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataCollection;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataProperties;
use pocketmine\network\mcpe\protocol\types\inventory\ContainerIds;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;
use pocketmine\player\Player;

class ArmorStandEntity extends Living{

	private const TAG_ARMOR_INVENTORY = "ArmorInventory";
	private const TAG_HELD_ITEM = "HeldItem";
	private const TAG_POSE = "Pose";

	public static function getNetworkTypeId() : string{
		return EntityIds::ARMOR_STAND;
	}

	protected int $maxDeadTicks = 0;

	private ArmorStandPose $pose;
	protected Item $itemInHand;
	protected bool $canBeMovedByCurrents = true;

	protected array $armorStandEntityTickers = [];

	protected static ArmorStandBehaviorRegistry $registry;

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(1.975, 0.5);
	}

	public function getName() : string{
		return "Armor Stand";
	}

	protected function syncNetworkData(EntityMetadataCollection $properties) : void{
		parent::syncNetworkData($properties);
		$properties->setInt(EntityMetadataProperties::ARMOR_STAND_POSE_INDEX, $this->pose->getNetworkId());
	}

	public function getDrops() : array{
		$drops = $this->getArmorInventory()->getContents();
		if(!$this->itemInHand->isNull()){
			$drops[] = $this->itemInHand;
		}
		$drops[] = VanillaItems::ARMOR_STAND();
		return $drops;
	}

	public function getItemInHand() : Item{
		return $this->itemInHand;
	}

	public function setItemInHand(Item $itemInHand) : void{
		$this->itemInHand = $itemInHand;
		$packet = MobEquipmentPacket::create($this->getId(), ItemStackWrapper::legacy(TypeConverter::getInstance()->coreItemStackToNet($this->getItemInHand())), 0, 0, ContainerIds::INVENTORY);
		foreach($this->getViewers() as $viewer){
			$viewer->getNetworkSession()->sendDataPacket($packet);
		}
	}

	public function getPose() : ArmorStandPose{
		return $this->pose;
	}

	public function setPose(ArmorStandPose $pose) : void{
		$this->pose = $pose;
		$this->networkPropertiesDirty = true;
		$this->scheduleUpdate();
	}

	protected function sendSpawnPacket(Player $player) : void{
		parent::sendSpawnPacket($player);
		$player->getNetworkSession()->sendDataPacket(MobEquipmentPacket::create($this->getId(), ItemStackWrapper::legacy(TypeConverter::getInstance()->coreItemStackToNet($this->getItemInHand())), 0, 0, ContainerIds::INVENTORY));
	}

	protected function addAttributes() : void{
		parent::addAttributes();
		$this->setMaxHealth(6);
	}

	protected function initEntity(CompoundTag $nbt) : void{
		parent::initEntity($nbt);

		$armorInventoryTag = $nbt->getListTag(self::TAG_ARMOR_INVENTORY);
		if($armorInventoryTag !== null){
			$armorInventory = $this->getArmorInventory();
			/** @var CompoundTag $tag */
			foreach($armorInventoryTag as $tag){
				$armorInventory->setItem($tag->getByte("slot"), Item::nbtDeserialize($tag));
			}
		}

		$itemInHandTag = $nbt->getCompoundTag(self::TAG_HELD_ITEM);
		$this->itemInHand = $itemInHandTag !== null ? Item::nbtDeserialize($itemInHandTag) : VanillaItems::AIR();

		$this->setPose(($tagPose = $nbt->getTag(self::TAG_POSE)) instanceof StringTag ?
			ArmorStandPoseRegistry::instance()->get($tagPose->getValue()) :
			ArmorStandPoseRegistry::instance()->default());
	}

	public function saveNBT() : CompoundTag{
		$nbt = parent::saveNBT();

		$armorPieces = [];
		foreach($this->getArmorInventory()->getContents() as $slot => $item){
			$armorPieces[] = $item->nbtSerialize($slot);
		}
		$nbt->setTag(self::TAG_ARMOR_INVENTORY, new ListTag($armorPieces, NBT::TAG_Compound));

		if(!$this->itemInHand->isNull()){
			$nbt->setTag(self::TAG_HELD_ITEM, $this->itemInHand->nbtSerialize());
		}

		$nbt->setString(self::TAG_POSE, ArmorStandPoseRegistry::instance()->getIdentifier($this->pose));
		return $nbt;
	}

	public function applyDamageModifiers(EntityDamageEvent $source) : void{
	}

	public function attack(EntityDamageEvent $source) : void{
		parent::attack($source);
		if($source instanceof EntityDamageByChildEntityEvent && $source->getChild() instanceof Arrow){
			$this->kill();
		}
	}

	public function knockBack(float $x, float $z, float $force = self::DEFAULT_KNOCKBACK_FORCE, ?float $verticalLimit = self::DEFAULT_KNOCKBACK_VERTICAL_LIMIT) : void{
	}

	public function actuallyKnockBack(float $x, float $z, float $force = 0.4, ?float $verticalLimit = 0.4) : void{
		parent::knockBack($x, $z, $force, $verticalLimit);
	}

	protected function doHitAnimation() : void{
		if(
			$this->lastDamageCause instanceof EntityDamageByEntityEvent &&
			$this->lastDamageCause->getCause() === EntityDamageEvent::CAUSE_ENTITY_ATTACK &&
			$this->lastDamageCause->getDamager() instanceof Player
		){
			$this->addArmorStandEntityTicker("ticker:wobble", new WobbleArmorStandEntityTicker($this));
		}
	}

	protected function startDeathAnimation() : void{
	}

	public function addArmorStandEntityTicker(string $identifier, ArmorStandEntityTicker $ticker) : void{
		$this->armorStandEntityTickers[$identifier] = $ticker;
		$this->scheduleUpdate();
	}

	public function removeArmorStandEntityTicker(string $identifier) : void{
		unset($this->armorStandEntityTickers[$identifier]);
	}

	public function canBeMovedByCurrents() : bool{
		return $this->canBeMovedByCurrents;
	}

	public function setCanBeMovedByCurrents(bool $canBeMovedByCurrents) : void{
		$this->canBeMovedByCurrents = $canBeMovedByCurrents;
	}

	protected function entityBaseTick(int $tickDiff = 1) : bool{
		$result = parent::entityBaseTick($tickDiff);

		foreach($this->armorStandEntityTickers as $identifier => $ticker){
			if(!$ticker->tick($this)){
				$this->removeArmorStandEntityTicker($identifier);
			}
		}

		return $result || count($this->armorStandEntityTickers) > 0;
	}

	protected function move(float $dx, float $dy, float $dz) : void{
		$from = $this->location->asLocation();
		parent::move($dx, $dy, $dz);
		$to = $this->location->asLocation();
		$ev = new ArmorStandMoveEvent($this, $from, $to);
		$ev->call();
	}

	public function onInteract(Player $player, Vector3 $clickPos) : bool{
		if(!$player->canInteract($clickPos, 8) || !$this->boundingBox->expandedCopy(0.25, 0.25, 0.25)->isVectorInside($clickPos)){
			return false;
		}

		if($player->isSneaking()){
			$oldPose = $this->getPose();
			$newPose = ArmorStandPoseRegistry::instance()->next($oldPose);
			$ev = new PlayerChangeArmorStandPoseEvent($this, $oldPose, $newPose, $player);
			$ev->call();
			if(!$ev->isCancelled()){
				$this->setPose($ev->getNewPose());
			}
		}else{
			self::$registry->get($player->getInventory()->getItemInHand())->handleEquipment($player, $this, $clickPos);
		}
		return true;
	}

	public function __construct(Location $location, ?CompoundTag $nbt = null){
		parent::__construct($location, $nbt);
		self::$registry = new ArmorStandBehaviorRegistry();
	}


}