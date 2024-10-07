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

namespace pocketmine\entity;

use DaveRandom\CallbackValidator\CallbackType;
use DaveRandom\CallbackValidator\ParameterType;
use DaveRandom\CallbackValidator\ReturnType;
use pocketmine\block\RuntimeBlockStateRegistry;
use pocketmine\data\bedrock\LegacyEntityIdToStringIdMap;
use pocketmine\data\bedrock\PotionTypeIdMap;
use pocketmine\data\bedrock\PotionTypeIds;
use pocketmine\data\SavedDataLoadingException;
use pocketmine\entity\EntityDataHelper as Helper;
use pocketmine\entity\mob\boss\EnderDragon;
use pocketmine\entity\mob\boss\Wither;
use pocketmine\entity\mob\hostile\Blaze;
use pocketmine\entity\mob\hostile\Bogged;
use pocketmine\entity\mob\hostile\Breeze;
use pocketmine\entity\mob\hostile\Creeper;
use pocketmine\entity\mob\hostile\ElderGuardian;
use pocketmine\entity\mob\hostile\Endermite;
use pocketmine\entity\mob\hostile\Evoker;
use pocketmine\entity\mob\hostile\Ghast;
use pocketmine\entity\mob\hostile\Guardian;
use pocketmine\entity\mob\hostile\Hoglin;
use pocketmine\entity\mob\hostile\Husk;
use pocketmine\entity\mob\hostile\MagmaCube;
use pocketmine\entity\mob\hostile\Phantom;
use pocketmine\entity\mob\hostile\PiglinBrute;
use pocketmine\entity\mob\hostile\Pillager;
use pocketmine\entity\mob\hostile\Ravager;
use pocketmine\entity\mob\hostile\Shulker;
use pocketmine\entity\mob\hostile\Silverfish;
use pocketmine\entity\mob\hostile\Skeleton;
use pocketmine\entity\mob\hostile\Slime;
use pocketmine\entity\mob\hostile\Stray;
use pocketmine\entity\mob\hostile\Vex;
use pocketmine\entity\mob\hostile\Vindicator;
use pocketmine\entity\mob\hostile\Warden;
use pocketmine\entity\mob\hostile\Witch;
use pocketmine\entity\mob\hostile\WitherSkeleton;
use pocketmine\entity\mob\hostile\Zoglin;
use pocketmine\entity\mob\hostile\Zombie;
use pocketmine\entity\mob\hostile\ZombieVillager;
use pocketmine\entity\mob\neutral\Bee;
use pocketmine\entity\mob\neutral\CaveSpider;
use pocketmine\entity\mob\neutral\Dolphin;
use pocketmine\entity\mob\neutral\Drowned;
use pocketmine\entity\mob\neutral\Enderman;
use pocketmine\entity\mob\neutral\Fox;
use pocketmine\entity\mob\neutral\Goat;
use pocketmine\entity\mob\neutral\IronGolem;
use pocketmine\entity\mob\neutral\Llama;
use pocketmine\entity\mob\neutral\Panda;
use pocketmine\entity\mob\neutral\Piglin;
use pocketmine\entity\mob\neutral\PolarBear;
use pocketmine\entity\mob\neutral\Spider;
use pocketmine\entity\mob\neutral\TraderLlama;
use pocketmine\entity\mob\neutral\Wolf;
use pocketmine\entity\mob\neutral\ZombifiedPiglin;
use pocketmine\entity\mob\passive\Allay;
use pocketmine\entity\mob\passive\Armadillo;
use pocketmine\entity\mob\passive\Axolotl;
use pocketmine\entity\mob\passive\Bat;
use pocketmine\entity\mob\passive\Camel;
use pocketmine\entity\mob\passive\Cat;
use pocketmine\entity\mob\passive\Chicken;
use pocketmine\entity\mob\passive\Cod;
use pocketmine\entity\mob\passive\Cow;
use pocketmine\entity\mob\passive\Donkey;
use pocketmine\entity\mob\passive\Frog;
use pocketmine\entity\mob\passive\GlowSquid;
use pocketmine\entity\mob\passive\Horse;
use pocketmine\entity\mob\passive\Mooshroom;
use pocketmine\entity\mob\passive\Mule;
use pocketmine\entity\mob\passive\Ocelot;
use pocketmine\entity\mob\passive\Parrot;
use pocketmine\entity\mob\passive\Pig;
use pocketmine\entity\mob\passive\Pufferfish;
use pocketmine\entity\mob\passive\Rabbit;
use pocketmine\entity\mob\passive\Salmon;
use pocketmine\entity\mob\passive\Sheep;
use pocketmine\entity\mob\passive\SkeletonHorse;
use pocketmine\entity\mob\passive\Sniffer;
use pocketmine\entity\mob\passive\SnowGolem;
use pocketmine\entity\mob\passive\Squid;
use pocketmine\entity\mob\passive\Strider;
use pocketmine\entity\mob\passive\Tadpole;
use pocketmine\entity\mob\passive\TropicalFish;
use pocketmine\entity\mob\passive\Turtle;
use pocketmine\entity\mob\passive\Villager;
use pocketmine\entity\mob\passive\WanderingTrader;
use pocketmine\entity\object\AreaEffectCloud;
use pocketmine\entity\object\EndCrystal;
use pocketmine\entity\object\ExperienceOrb;
use pocketmine\entity\object\FallingBlock;
use pocketmine\entity\object\ItemEntity;
use pocketmine\entity\object\Painting;
use pocketmine\entity\object\PaintingMotive;
use pocketmine\entity\object\PrimedTNT;
use pocketmine\entity\projectile\Arrow;
use pocketmine\entity\projectile\Egg;
use pocketmine\entity\projectile\EnderPearl;
use pocketmine\entity\projectile\ExperienceBottle;
use pocketmine\entity\projectile\Snowball;
use pocketmine\entity\projectile\SplashPotion;
use pocketmine\entity\projectile\WindCharge;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\nbt\NbtException;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\Utils;
use pocketmine\world\World;
use function count;
use function reset;

/**
 * This class manages the creation of entities loaded from disk.
 * You need to register your entity into this factory if you want to load/save your entity on disk (saving with chunks).
 */
final class EntityFactory{
	use SingletonTrait;

	public const TAG_IDENTIFIER = "identifier"; //TAG_String
	public const TAG_LEGACY_ID = "id"; //TAG_Int

	/**
	 * @var \Closure[] save ID => creator function
	 * @phpstan-var array<int|string, \Closure(World, CompoundTag) : Entity>
	 */
	private array $creationFuncs = [];
	/**
	 * @var string[]
	 * @phpstan-var array<class-string<Entity>, string>
	 */
	private array $saveNames = [];

	public function __construct(){
		//define legacy save IDs first - use them for saving for maximum compatibility with Minecraft PC
		//TODO: index them by version to allow proper multi-save compatibility

		$this->register(AreaEffectCloud::class, function(World $world, CompoundTag $nbt) : AreaEffectCloud {
			$potionType = PotionTypeIdMap::getInstance()->fromId($nbt->getShort(AreaEffectCloud::TAG_POTION_ID, PotionTypeIds::WATER));
			if ($potionType === null) {
				throw new SavedDataLoadingException("No such potion type");
			}
			return new AreaEffectCloud(
				Helper::parseLocation($nbt, $world),
				$potionType,
				$nbt->getFloat(AreaEffectCloud::TAG_INITIAL_RADIUS, AreaEffectCloud::DEFAULT_RADIUS),
				$nbt
			);
		}, ["AreaEffectCloud", "minecraft:area_effect_cloud"]);
		$this->register(Arrow::class, function(World $world, CompoundTag $nbt) : Arrow{
			return new Arrow(Helper::parseLocation($nbt, $world), null, $nbt->getByte(Arrow::TAG_CRIT, 0) === 1, $nbt);
		}, ['Arrow', 'minecraft:arrow']);

		$this->register(Egg::class, function(World $world, CompoundTag $nbt) : Egg{
			return new Egg(Helper::parseLocation($nbt, $world), null, $nbt);
		}, ['Egg', 'minecraft:egg']);

		$this->register(EndCrystal::class, function(World $world, CompoundTag $nbt) : EndCrystal {
			return new EndCrystal(Helper::parseLocation($nbt, $world), $nbt);
		}, ["EnderCrystal", "minecraft:ender_crystal"]);
		$this->register(EnderPearl::class, function(World $world, CompoundTag $nbt) : EnderPearl{
			return new EnderPearl(Helper::parseLocation($nbt, $world), null, $nbt);
		}, ['ThrownEnderpearl', 'minecraft:ender_pearl']);

		$this->register(ExperienceBottle::class, function(World $world, CompoundTag $nbt) : ExperienceBottle{
			return new ExperienceBottle(Helper::parseLocation($nbt, $world), null, $nbt);
		}, ['ThrownExpBottle', 'minecraft:xp_bottle']);

		$this->register(ExperienceOrb::class, function(World $world, CompoundTag $nbt) : ExperienceOrb{
			$value = 1;
			if(($valuePcTag = $nbt->getTag(ExperienceOrb::TAG_VALUE_PC)) instanceof ShortTag){ //PC
				$value = $valuePcTag->getValue();
			}elseif(($valuePeTag = $nbt->getTag(ExperienceOrb::TAG_VALUE_PE)) instanceof IntTag){ //PE save format
				$value = $valuePeTag->getValue();
			}

			return new ExperienceOrb(Helper::parseLocation($nbt, $world), $value, $nbt);
		}, ['XPOrb', 'minecraft:xp_orb']);

		$this->register(FallingBlock::class, function(World $world, CompoundTag $nbt) : FallingBlock{
			return new FallingBlock(Helper::parseLocation($nbt, $world), FallingBlock::parseBlockNBT(RuntimeBlockStateRegistry::getInstance(), $nbt), $nbt);
		}, ['FallingSand', 'minecraft:falling_block']);

		$this->register(ItemEntity::class, function(World $world, CompoundTag $nbt) : ItemEntity{
			$itemTag = $nbt->getCompoundTag(ItemEntity::TAG_ITEM);
			if($itemTag === null){
				throw new SavedDataLoadingException("Expected \"" . ItemEntity::TAG_ITEM . "\" NBT tag not found");
			}

			$item = Item::nbtDeserialize($itemTag);
			if($item->isNull()){
				throw new SavedDataLoadingException("Item is invalid");
			}
			return new ItemEntity(Helper::parseLocation($nbt, $world), $item, $nbt);
		}, ['Item', 'minecraft:item']);

		$this->register(Painting::class, function(World $world, CompoundTag $nbt) : Painting{
			$motive = PaintingMotive::getMotiveByName($nbt->getString(Painting::TAG_MOTIVE));
			if($motive === null){
				throw new SavedDataLoadingException("Unknown painting motive");
			}
			$blockIn = new Vector3($nbt->getInt(Painting::TAG_TILE_X), $nbt->getInt(Painting::TAG_TILE_Y), $nbt->getInt(Painting::TAG_TILE_Z));
			if(($directionTag = $nbt->getTag(Painting::TAG_DIRECTION_BE)) instanceof ByteTag){
				$facing = Painting::DATA_TO_FACING[$directionTag->getValue()] ?? Facing::NORTH;
			}elseif(($facingTag = $nbt->getTag(Painting::TAG_FACING_JE)) instanceof ByteTag){
				$facing = Painting::DATA_TO_FACING[$facingTag->getValue()] ?? Facing::NORTH;
			}else{
				throw new SavedDataLoadingException("Missing facing info");
			}

			return new Painting(Helper::parseLocation($nbt, $world), $blockIn, $facing, $motive, $nbt);
		}, ['Painting', 'minecraft:painting']);

		$this->register(PrimedTNT::class, function(World $world, CompoundTag $nbt) : PrimedTNT{
			return new PrimedTNT(Helper::parseLocation($nbt, $world), $nbt);
		}, ['PrimedTnt', 'PrimedTNT', 'minecraft:tnt']);

		$this->register(Snowball::class, function(World $world, CompoundTag $nbt) : Snowball{
			return new Snowball(Helper::parseLocation($nbt, $world), null, $nbt);
		}, ['Snowball', 'minecraft:snowball']);

		$this->register(SplashPotion::class, function(World $world, CompoundTag $nbt) : SplashPotion{
			$potionType = PotionTypeIdMap::getInstance()->fromId($nbt->getShort(SplashPotion::TAG_POTION_ID, PotionTypeIds::WATER));
			if($potionType === null){
				throw new SavedDataLoadingException("No such potion type");
			}
			return new SplashPotion(Helper::parseLocation($nbt, $world), null, $potionType, $nbt);
		}, ['ThrownPotion', 'minecraft:potion', 'thrownpotion']);

		$this->register(Squid::class, function(World $world, CompoundTag $nbt) : Squid{
			return new Squid(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Squid', 'minecraft:squid']);

		$this->register(Villager::class, function(World $world, CompoundTag $nbt) : Villager{
			return new Villager(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Villager', 'minecraft:villager']);

		$this->register(WindCharge::class, function(World $world, CompoundTag $nbt) : WindCharge {
			return new WindCharge(Helper::parseLocation($nbt, $world), null, $nbt);
		}, ['Wind Charge', 'minecraft:wind_charge']);

		$this->register(Zombie::class, function(World $world, CompoundTag $nbt) : Zombie{
			return new Zombie(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Zombie', 'minecraft:zombie']);

		$this->register(Allay::class, function(World $world, CompoundTag $nbt) : Allay{
			return new Allay(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Allay', 'minecraft:allay']);

		$this->register(Armadillo::class, function(World $world, CompoundTag $nbt) : Armadillo{
			return new Armadillo(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Armadillo', 'minecraft:armadillo']);

		$this->register(Axolotl::class, function(World $world, CompoundTag $nbt) : Axolotl{
			return new Axolotl(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Axolotl', 'minecraft:axolotl']);

		$this->register(Bat::class, function(World $world, CompoundTag $nbt) : Bat{
			return new Bat(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Bat', 'minecraft:bat']);

		$this->register(Camel::class, function(World $world, CompoundTag $nbt) : Camel{
			return new Camel(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Camel', 'minecraft:camel']);

		$this->register(Cat::class, function(World $world, CompoundTag $nbt) : Cat{
			return new Cat(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Cat', 'minecraft:cat']);

		$this->register(Chicken::class, function(World $world, CompoundTag $nbt) : Chicken{
			return new Chicken(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Chicken', 'minecraft:chicken']);

		$this->register(Cod::class, function(World $world, CompoundTag $nbt) : Cod{
			return new Cod(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Cod', 'minecraft:cod']);

		$this->register(Cow::class, function(World $world, CompoundTag $nbt) : Cow{
			return new Cow(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Cow', 'minecraft:cow']);

		$this->register(Donkey::class, function(World $world, CompoundTag $nbt) : Donkey{
			return new Donkey(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Donkey', 'minecraft:donkey']);

		$this->register(Frog::class, function(World $world, CompoundTag $nbt) : Frog{
			return new Frog(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Frog', 'minecraft:frog']);

		$this->register(GlowSquid::class, function(World $world, CompoundTag $nbt) : GlowSquid{
			return new GlowSquid(Helper::parseLocation($nbt, $world), $nbt);
		}, ['GlowSquid', 'minecraft:glow_squid']);

		$this->register(Horse::class, function(World $world, CompoundTag $nbt) : Horse{
			return new Horse(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Horse', 'minecraft:horse']);

		$this->register(Mooshroom::class, function(World $world, CompoundTag $nbt) : Mooshroom{
			return new Mooshroom(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Mooshroom', 'minecraft:mooshroom']);

		$this->register(Mule::class, function(World $world, CompoundTag $nbt) : Mule{
			return new Mule(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Mule', 'minecraft:mule']);

		$this->register(Ocelot::class, function(World $world, CompoundTag $nbt) : Ocelot{
			return new Ocelot(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Ocelot', 'minecraft:ocelot']);

		$this->register(Parrot::class, function(World $world, CompoundTag $nbt) : Parrot{
			return new Parrot(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Parrot', 'minecraft:parrot']);

		$this->register(Pig::class, function(World $world, CompoundTag $nbt) : Pig{
			return new Pig(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Pig', 'minecraft:pig']);

		$this->register(Pufferfish::class, function(World $world, CompoundTag $nbt) : Pufferfish{
			return new Pufferfish(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Pufferfish', 'minecraft:pufferfish']);

		$this->register(Rabbit::class, function(World $world, CompoundTag $nbt) : Rabbit{
			return new Rabbit(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Rabbit', 'minecraft:rabbit']);

		$this->register(Salmon::class, function(World $world, CompoundTag $nbt) : Salmon{
			return new Salmon(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Salmon', 'minecraft:salmon']);

		$this->register(Sheep::class, function(World $world, CompoundTag $nbt) : Sheep{
			return new Sheep(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Sheep', 'minecraft:sheep']);

		$this->register(SkeletonHorse::class, function(World $world, CompoundTag $nbt) : SkeletonHorse{
			return new SkeletonHorse(Helper::parseLocation($nbt, $world), $nbt);
		}, ['SkeletonHorse', 'minecraft:skeleton_horse']);

		$this->register(Sniffer::class, function(World $world, CompoundTag $nbt) : Sniffer{
			return new Sniffer(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Sniffer', 'minecraft:sniffer']);

		$this->register(SnowGolem::class, function(World $world, CompoundTag $nbt) : SnowGolem{
			return new SnowGolem(Helper::parseLocation($nbt, $world), $nbt);
		}, ['SnowGolem', 'minecraft:snow_golem']);

		$this->register(Strider::class, function(World $world, CompoundTag $nbt) : Strider{
			return new Strider(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Strider', 'minecraft:strider']);

		$this->register(Tadpole::class, function(World $world, CompoundTag $nbt) : Tadpole{
			return new Tadpole(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Tadpole', 'minecraft:tadpole']);

		$this->register(TropicalFish::class, function(World $world, CompoundTag $nbt) : TropicalFish{
			return new TropicalFish(Helper::parseLocation($nbt, $world), $nbt);
		}, ['TropicalFish', 'minecraft:tropical_fish']);

		$this->register(Turtle::class, function(World $world, CompoundTag $nbt) : Turtle{
			return new Turtle(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Turtle', 'minecraft:turtle']);

		$this->register(WanderingTrader::class, function(World $world, CompoundTag $nbt) : WanderingTrader{
			return new WanderingTrader(Helper::parseLocation($nbt, $world), $nbt);
		}, ['WanderingTrader', 'minecraft:wandering_trader']);

		$this->register(Bee::class, function(World $world, CompoundTag $nbt) : Bee {
			return new Bee(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Bee', 'minecraft:bee']);

		$this->register(CaveSpider::class, function(World $world, CompoundTag $nbt) : CaveSpider {
			return new CaveSpider(Helper::parseLocation($nbt, $world), $nbt);
		}, ['CaveSpider', 'minecraft:cave_spider']);

		$this->register(Dolphin::class, function(World $world, CompoundTag $nbt) : Dolphin {
			return new Dolphin(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Dolphin', 'minecraft:dolphin']);

		$this->register(Drowned::class, function(World $world, CompoundTag $nbt) : Drowned {
			return new Drowned(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Drowned', 'minecraft:drowned']);

		$this->register(Enderman::class, function(World $world, CompoundTag $nbt) : Enderman {
			return new Enderman(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Enderman', 'minecraft:enderman']);

		$this->register(Fox::class, function(World $world, CompoundTag $nbt) : Fox {
			return new Fox(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Fox', 'minecraft:fox']);

		$this->register(Goat::class, function(World $world, CompoundTag $nbt) : Goat {
			return new Goat(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Goat', 'minecraft:goat']);

		$this->register(IronGolem::class, function(World $world, CompoundTag $nbt) : IronGolem {
			return new IronGolem(Helper::parseLocation($nbt, $world), $nbt);
		}, ['IronGolem', 'minecraft:iron_golem']);

		$this->register(Llama::class, function(World $world, CompoundTag $nbt) : Llama {
			return new Llama(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Llama', 'minecraft:llama']);

		$this->register(Panda::class, function(World $world, CompoundTag $nbt) : Panda {
			return new Panda(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Panda', 'minecraft:panda']);

		$this->register(Piglin::class, function(World $world, CompoundTag $nbt) : Piglin {
			return new Piglin(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Piglin', 'minecraft:piglin']);

		$this->register(PolarBear::class, function(World $world, CompoundTag $nbt) : PolarBear {
			return new PolarBear(Helper::parseLocation($nbt, $world), $nbt);
		}, ['PolarBear', 'minecraft:polar_bear']);

		$this->register(Spider::class, function(World $world, CompoundTag $nbt) : Spider {
			return new Spider(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Spider', 'minecraft:spider']);

		$this->register(TraderLlama::class, function(World $world, CompoundTag $nbt) : TraderLlama {
			return new TraderLlama(Helper::parseLocation($nbt, $world), $nbt);
		}, ['TraderLlama', 'minecraft:trader_llama']);

		$this->register(Wolf::class, function(World $world, CompoundTag $nbt) : Wolf {
			return new Wolf(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Wolf', 'minecraft:wolf']);

		$this->register(ZombifiedPiglin::class, function(World $world, CompoundTag $nbt) : ZombifiedPiglin {
			return new ZombifiedPiglin(Helper::parseLocation($nbt, $world), $nbt);
		}, ['ZombifiedPiglin', 'minecraft:zombified_piglin']);

		$this->register(EnderDragon::class, function(World $world, CompoundTag $nbt) : EnderDragon {
			return new EnderDragon(Helper::parseLocation($nbt, $world), $nbt);
		}, ['EnderDragon', 'minecraft:ender_dragon']);

		$this->register(Wither::class, function(World $world, CompoundTag $nbt) : Wither {
			return new Wither(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Wither', 'minecraft:wither']);

		$this->register(Blaze::class, function(World $world, CompoundTag $nbt) : Blaze {
			return new Blaze(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Blaze', 'minecraft:blaze']);

		$this->register(Bogged::class, function(World $world, CompoundTag $nbt) : Bogged {
			return new Bogged(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Bogged', 'minecraft:bogged']);

		$this->register(Breeze::class, function(World $world, CompoundTag $nbt) : Breeze {
			return new Breeze(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Breeze', 'minecraft:breeze']);

		$this->register(Creeper::class, function(World $world, CompoundTag $nbt) : Creeper {
			return new Creeper(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Creeper', 'minecraft:creeper']);

		$this->register(ElderGuardian::class, function(World $world, CompoundTag $nbt) : ElderGuardian {
			return new ElderGuardian(Helper::parseLocation($nbt, $world), $nbt);
		}, ['ElderGuardian', 'minecraft:elder_guardian']);

		$this->register(Endermite::class, function(World $world, CompoundTag $nbt) : Endermite {
			return new Endermite(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Endermite', 'minecraft:endermite']);

		$this->register(Evoker::class, function(World $world, CompoundTag $nbt) : Evoker {
			return new Evoker(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Evoker', 'minecraft:evoker']);

		$this->register(Ghast::class, function(World $world, CompoundTag $nbt) : Ghast {
			return new Ghast(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Ghast', 'minecraft:ghast']);

		$this->register(Guardian::class, function(World $world, CompoundTag $nbt) : Guardian {
			return new Guardian(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Guardian', 'minecraft:guardian']);

		$this->register(Hoglin::class, function(World $world, CompoundTag $nbt) : Hoglin {
			return new Hoglin(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Hoglin', 'minecraft:hoglin']);

		$this->register(Husk::class, function(World $world, CompoundTag $nbt) : Husk {
			return new Husk(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Husk', 'minecraft:husk']);

		$this->register(MagmaCube::class, function(World $world, CompoundTag $nbt) : MagmaCube {
			return new MagmaCube(Helper::parseLocation($nbt, $world), $nbt);
		}, ['MagmaCube', 'minecraft:magma_cube']);

		$this->register(Phantom::class, function(World $world, CompoundTag $nbt) : Phantom {
			return new Phantom(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Phantom', 'minecraft:phantom']);

		$this->register(PiglinBrute::class, function(World $world, CompoundTag $nbt) : PiglinBrute {
			return new PiglinBrute(Helper::parseLocation($nbt, $world), $nbt);
		}, ['PiglinBrute', 'minecraft:piglin_brute']);

		$this->register(Pillager::class, function(World $world, CompoundTag $nbt) : Pillager {
			return new Pillager(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Pillager', 'minecraft:pillager']);

		$this->register(Ravager::class, function(World $world, CompoundTag $nbt) : Ravager {
			return new Ravager(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Ravager', 'minecraft:ravager']);

		$this->register(Shulker::class, function(World $world, CompoundTag $nbt) : Shulker {
			return new Shulker(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Shulker', 'minecraft:shulker']);

		$this->register(Silverfish::class, function(World $world, CompoundTag $nbt) : Silverfish {
			return new Silverfish(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Silverfish', 'minecraft:silverfish']);

		$this->register(Skeleton::class, function(World $world, CompoundTag $nbt) : Skeleton {
			return new Skeleton(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Skeleton', 'minecraft:skeleton']);

		$this->register(Slime::class, function(World $world, CompoundTag $nbt) : Slime {
			return new Slime(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Slime', 'minecraft:slime']);

		$this->register(Stray::class, function(World $world, CompoundTag $nbt) : Stray {
			return new Stray(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Stray', 'minecraft:stray']);

		$this->register(Vex::class, function(World $world, CompoundTag $nbt) : Vex {
			return new Vex(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Vex', 'minecraft:vex']);

		$this->register(Vindicator::class, function(World $world, CompoundTag $nbt) : Vindicator {
			return new Vindicator(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Vindicator', 'minecraft:vindicator']);

		$this->register(Warden::class, function(World $world, CompoundTag $nbt) : Warden {
			return new Warden(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Warden', 'minecraft:warden']);

		$this->register(Witch::class, function(World $world, CompoundTag $nbt) : Witch {
			return new Witch(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Witch', 'minecraft:witch']);

		$this->register(WitherSkeleton::class, function(World $world, CompoundTag $nbt) : WitherSkeleton {
			return new WitherSkeleton(Helper::parseLocation($nbt, $world), $nbt);
		}, ['WitherSkeleton', 'minecraft:wither_skeleton']);

		$this->register(Zoglin::class, function(World $world, CompoundTag $nbt) : Zoglin {
			return new Zoglin(Helper::parseLocation($nbt, $world), $nbt);
		}, ['Zoglin', 'minecraft:zoglin']);

		$this->register(ZombieVillager::class, function(World $world, CompoundTag $nbt) : ZombieVillager {
			return new ZombieVillager(Helper::parseLocation($nbt, $world), $nbt);
		}, ['ZombieVillager', 'minecraft:zombie_villager']);


		$this->register(Human::class, function(World $world, CompoundTag $nbt) : Human{
			return new Human(Helper::parseLocation($nbt, $world), Human::parseSkinNBT($nbt), $nbt);
		}, ['Human']);
	}

	/**
	 * Registers an entity type into the index.
	 *
	 * @param string   $className Class that extends Entity
	 * @param string[] $saveNames An array of save names which this entity might be saved under.
	 * @phpstan-param class-string<Entity> $className
	 * @phpstan-param list<string> $saveNames
	 * @phpstan-param \Closure(World $world, CompoundTag $nbt) : Entity $creationFunc
	 *
	 * NOTE: The first save name in the $saveNames array will be used when saving the entity to disk.
	 *
	 * @throws \InvalidArgumentException
	 */
	public function register(string $className, \Closure $creationFunc, array $saveNames) : void{
		if(count($saveNames) === 0){
			throw new \InvalidArgumentException("At least one save name must be provided");
		}
		Utils::testValidInstance($className, Entity::class);
		Utils::validateCallableSignature(new CallbackType(
			new ReturnType(Entity::class),
			new ParameterType("world", World::class),
			new ParameterType("nbt", CompoundTag::class)
		), $creationFunc);

		foreach($saveNames as $name){
			$this->creationFuncs[$name] = $creationFunc;
		}

		$this->saveNames[$className] = reset($saveNames);
	}

	/**
	 * Creates an entity from data stored on a chunk.
	 *
	 * @throws SavedDataLoadingException
	 * @internal
	 */
	public function createFromData(World $world, CompoundTag $nbt) : ?Entity{
		try{
			$saveId = $nbt->getTag(self::TAG_IDENTIFIER) ?? $nbt->getTag(self::TAG_LEGACY_ID);
			$func = null;
			if($saveId instanceof StringTag){
				$func = $this->creationFuncs[$saveId->getValue()] ?? null;
			}elseif($saveId instanceof IntTag){ //legacy MCPE format
				$stringId = LegacyEntityIdToStringIdMap::getInstance()->legacyToString($saveId->getValue() & 0xff);
				$func = $stringId !== null ? $this->creationFuncs[$stringId] ?? null : null;
			}
			if($func === null){
				return null;
			}
			/** @var Entity $entity */
			$entity = $func($world, $nbt);

			return $entity;
		}catch(NbtException $e){
			throw new SavedDataLoadingException($e->getMessage(), 0, $e);
		}
	}

	public function injectSaveId(string $class, CompoundTag $saveData) : void{
		if(isset($this->saveNames[$class])){
			$saveData->setTag(self::TAG_IDENTIFIER, new StringTag($this->saveNames[$class]));
		}else{
			throw new \InvalidArgumentException("Entity $class is not registered");
		}
	}

	/**
	 * @phpstan-param class-string<Entity> $class
	 */
	public function getSaveId(string $class) : string{
		if(isset($this->saveNames[$class])){
			return $this->saveNames[$class];
		}
		throw new \InvalidArgumentException("Entity $class is not registered");
	}
}
