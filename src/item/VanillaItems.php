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

namespace pocketmine\item;

use pocketmine\block\utils\RecordType;
use pocketmine\block\VanillaBlocks as Blocks;
use pocketmine\entity\ambient\Bat;
use pocketmine\entity\animal\Chicken;
use pocketmine\entity\animal\Cow;
use pocketmine\entity\animal\MooshroomCow;
use pocketmine\entity\animal\Pig;
use pocketmine\entity\animal\Sheep;
use pocketmine\entity\Entity;
use pocketmine\entity\golem\IronGolem;
use pocketmine\entity\golem\SnowGolem;
use pocketmine\entity\Location;
//use pocketmine\entity\mob\hostile\Zombie;
//use pocketmine\entity\mob\passive\Squid;
//use pocketmine\entity\mob\passive\Villager;
use pocketmine\entity\monster\CaveSpider;
use pocketmine\entity\monster\Creeper;
use pocketmine\entity\monster\Enderman;
use pocketmine\entity\monster\Endermite;
use pocketmine\entity\monster\Slime;
use pocketmine\entity\monster\Spider;
use pocketmine\entity\monster\Zombie;
use pocketmine\entity\Villager;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\enchantment\ItemEnchantmentTags as EnchantmentTags;
use pocketmine\item\ItemIdentifier as IID;
use pocketmine\item\ItemTypeIds as Ids;
use pocketmine\item\VanillaArmorMaterials as ArmorMaterials;
use pocketmine\math\Vector3;
use pocketmine\utils\CloningRegistryTrait;
use pocketmine\world\World;
//use pocketmine\entity\mob\boss\EnderDragon;
//use pocketmine\entity\mob\boss\Wither;
//use pocketmine\entity\mob\hostile\Blaze;
//use pocketmine\entity\mob\hostile\Bogged;
//use pocketmine\entity\mob\hostile\Breeze;
//use pocketmine\entity\mob\hostile\Creeper;
//use pocketmine\entity\mob\hostile\ElderGuardian;
//use pocketmine\entity\mob\hostile\Endermite;
//use pocketmine\entity\mob\hostile\Evoker;
//use pocketmine\entity\mob\hostile\Ghast;
//use pocketmine\entity\mob\hostile\Guardian;
//use pocketmine\entity\mob\hostile\Hoglin;
//use pocketmine\entity\mob\hostile\Husk;
//use pocketmine\entity\mob\hostile\MagmaCube;
//use pocketmine\entity\mob\hostile\Phantom;
//use pocketmine\entity\mob\hostile\PiglinBrute;
//use pocketmine\entity\mob\hostile\Pillager;
//use pocketmine\entity\mob\hostile\Ravager;
//use pocketmine\entity\mob\hostile\Shulker;
//use pocketmine\entity\mob\hostile\Silverfish;
//use pocketmine\entity\mob\hostile\Skeleton;
//use pocketmine\entity\mob\hostile\Slime;
//use pocketmine\entity\mob\hostile\Stray;
//use pocketmine\entity\mob\hostile\Vex;
//use pocketmine\entity\mob\hostile\Vindicator;
//use pocketmine\entity\mob\hostile\Warden;
//use pocketmine\entity\mob\hostile\Witch;
//use pocketmine\entity\mob\hostile\WitherSkeleton;
//use pocketmine\entity\mob\hostile\Zoglin;
//use pocketmine\entity\mob\hostile\ZombieVillager;
//use pocketmine\entity\mob\neutral\Bee;
//use pocketmine\entity\mob\neutral\CaveSpider;
//use pocketmine\entity\mob\neutral\Dolphin;
//use pocketmine\entity\mob\neutral\Drowned;
//use pocketmine\entity\mob\neutral\Enderman;
//use pocketmine\entity\mob\neutral\Fox;
//use pocketmine\entity\mob\neutral\Goat;
//use pocketmine\entity\mob\neutral\IronGolem;
//use pocketmine\entity\mob\neutral\Llama;
//use pocketmine\entity\mob\neutral\Panda;
//use pocketmine\entity\mob\neutral\Piglin;
//use pocketmine\entity\mob\neutral\PolarBear;
//use pocketmine\entity\mob\neutral\Spider;
//use pocketmine\entity\mob\neutral\TraderLlama;
//use pocketmine\entity\mob\neutral\Wolf;
//use pocketmine\entity\mob\neutral\ZombifiedPiglin;
//use pocketmine\entity\mob\passive\Allay;
//use pocketmine\entity\mob\passive\Armadillo;
//use pocketmine\entity\mob\passive\Axolotl;
//use pocketmine\entity\mob\passive\Bat;
//use pocketmine\entity\mob\passive\Camel;
//use pocketmine\entity\mob\passive\Cat;
//use pocketmine\entity\mob\passive\Chicken;
//use pocketmine\entity\mob\passive\Cod;
//use pocketmine\entity\mob\passive\Cow;
//use pocketmine\entity\mob\passive\Donkey;
//use pocketmine\entity\mob\passive\Frog;
//use pocketmine\entity\mob\passive\GlowSquid;
//use pocketmine\entity\mob\passive\Horse;
//use pocketmine\entity\mob\passive\Mooshroom;
//use pocketmine\entity\mob\passive\Mule;
//use pocketmine\entity\mob\passive\Ocelot;
//use pocketmine\entity\mob\passive\Parrot;
//use pocketmine\entity\mob\passive\Pig;
//use pocketmine\entity\mob\passive\Pufferfish as EntityPufferfish;
//use pocketmine\entity\mob\passive\Rabbit;
//use pocketmine\entity\mob\passive\Salmon;
//use pocketmine\entity\mob\passive\Sheep;
//use pocketmine\entity\mob\passive\SkeletonHorse;
//use pocketmine\entity\mob\passive\Sniffer;
//use pocketmine\entity\mob\passive\SnowGolem;
//use pocketmine\entity\mob\passive\Strider;
//use pocketmine\entity\mob\passive\Tadpole;
//use pocketmine\entity\mob\passive\TropicalFish;
//use pocketmine\entity\mob\passive\Turtle;
//use pocketmine\entity\mob\passive\WanderingTrader;
use function strtolower;

/**
 * This doc-block is generated automatically, do not modify it manually.
 * This must be regenerated whenever registry members are added, removed or changed.
 * @see build/generate-registry-annotations.php
 * @generate-registry-docblock
 *
 * @method static Boat ACACIA_BOAT()
 * @method static Boat ACACIA_CHEST_BOAT()
 * @method static ItemBlockWallOrFloor ACACIA_HANGING_SIGN()
 * @method static ItemBlockWallOrFloor ACACIA_SIGN()
 * @method static ItemBlock AIR()
 * @method static Item AMETHYST_SHARD()
 * @method static PotterySherd ANGLER_POTTERY_SHERD()
 * @method static Apple APPLE()
 * @method static PotterySherd ARCHER_POTTERY_SHERD()
 * @method static ArmadilloShute ARMADILLO_SHUTE()
 * @method static ArmorStand ARMOR_STAND()
 * @method static PotterySherd ARMS_UP_POTTERY_SHERD()
 * @method static Arrow ARROW()
 * @method static BakedPotato BAKED_POTATO()
 * @method static Balloon BALLOON()
 * @method static Bamboo BAMBOO()
 * @method static Boat BAMBOO_CHEST_RAFT()
 * @method static ItemBlockWallOrFloor BAMBOO_HANGING_SIGN()
 * @method static Boat BAMBOO_RAFT()
 * @method static ItemBlockWallOrFloor BAMBOO_SIGN()
 * @method static Banner BANNER()
 * @method static SpawnEgg BAT_SPAWN_EGG()
 * @method static Beetroot BEETROOT()
 * @method static BeetrootSeeds BEETROOT_SEEDS()
 * @method static BeetrootSoup BEETROOT_SOUP()
 * @method static Boat BIRCH_BOAT()
 * @method static Boat BIRCH_CHEST_BOAT()
 * @method static ItemBlockWallOrFloor BIRCH_HANGING_SIGN()
 * @method static ItemBlockWallOrFloor BIRCH_SIGN()
 * @method static PotterySherd BLADE_POTTERY_SHERD()
 * @method static Item BLAZE_POWDER()
 * @method static BlazeRod BLAZE_ROD()
 * @method static Item BLEACH()
 * @method static Item BOLT_ARMOR_TRIM_SMITHING_TEMPLATE()
 * @method static Item BONE()
 * @method static Fertilizer BONE_MEAL()
 * @method static Book BOOK()
 * @method static BannerPattern BORDURE_INDENTED_BANNER_PATTERN()
 * @method static Bow BOW()
 * @method static Bowl BOWL()
 * @method static Bread BREAD()
 * @method static BreezeRod BREEZE_ROD()
 * @method static PotterySherd BREWER_POTTERY_SHERD()
 * @method static Item BRICK()
 * @method static Brush BRUSH()
 * @method static Bucket BUCKET()
 * @method static Bundle BUNDLE()
 * @method static PotterySherd BURN_POTTERY_SHERD()
 * @method static Carrot CARROT()
 * @method static CarrotOnAStick CARROT_ON_A_STICK()
 * @method static SpawnEgg CAVE_SPIDER_SPAWN_EGG()
 * @method static Armor CHAINMAIL_BOOTS()
 * @method static Armor CHAINMAIL_CHESTPLATE()
 * @method static Armor CHAINMAIL_HELMET()
 * @method static Armor CHAINMAIL_LEGGINGS()
 * @method static Coal CHARCOAL()
 * @method static Item CHEMICAL_ALUMINIUM_OXIDE()
 * @method static Item CHEMICAL_AMMONIA()
 * @method static Item CHEMICAL_BARIUM_SULPHATE()
 * @method static Item CHEMICAL_BENZENE()
 * @method static Item CHEMICAL_BORON_TRIOXIDE()
 * @method static Item CHEMICAL_CALCIUM_BROMIDE()
 * @method static Item CHEMICAL_CALCIUM_CHLORIDE()
 * @method static Item CHEMICAL_CERIUM_CHLORIDE()
 * @method static Item CHEMICAL_CHARCOAL()
 * @method static Item CHEMICAL_CRUDE_OIL()
 * @method static Item CHEMICAL_GLUE()
 * @method static Item CHEMICAL_HYDROGEN_PEROXIDE()
 * @method static Item CHEMICAL_HYPOCHLORITE()
 * @method static Item CHEMICAL_INK()
 * @method static Item CHEMICAL_IRON_SULPHIDE()
 * @method static Item CHEMICAL_LATEX()
 * @method static Item CHEMICAL_LITHIUM_HYDRIDE()
 * @method static Item CHEMICAL_LUMINOL()
 * @method static Item CHEMICAL_MAGNESIUM_NITRATE()
 * @method static Item CHEMICAL_MAGNESIUM_OXIDE()
 * @method static Item CHEMICAL_MAGNESIUM_SALTS()
 * @method static Item CHEMICAL_MERCURIC_CHLORIDE()
 * @method static Item CHEMICAL_POLYETHYLENE()
 * @method static Item CHEMICAL_POTASSIUM_CHLORIDE()
 * @method static Item CHEMICAL_POTASSIUM_IODIDE()
 * @method static Item CHEMICAL_RUBBISH()
 * @method static Item CHEMICAL_SALT()
 * @method static Item CHEMICAL_SOAP()
 * @method static Item CHEMICAL_SODIUM_ACETATE()
 * @method static Item CHEMICAL_SODIUM_FLUORIDE()
 * @method static Item CHEMICAL_SODIUM_HYDRIDE()
 * @method static Item CHEMICAL_SODIUM_HYDROXIDE()
 * @method static Item CHEMICAL_SODIUM_HYPOCHLORITE()
 * @method static Item CHEMICAL_SODIUM_OXIDE()
 * @method static Item CHEMICAL_SUGAR()
 * @method static Item CHEMICAL_SULPHATE()
 * @method static Item CHEMICAL_TUNGSTEN_CHLORIDE()
 * @method static Item CHEMICAL_WATER()
 * @method static Boat CHERRY_BOAT()
 * @method static Boat CHERRY_CHEST_BOAT()
 * @method static ItemBlockWallOrFloor CHERRY_HANGING_SIGN()
 * @method static ItemBlockWallOrFloor CHERRY_SIGN()
 * @method static Minecart CHEST_MINECART()
 * @method static SpawnEgg CHICKEN_SPAWN_EGG()
 * @method static ChorusFruit CHORUS_FRUIT()
 * @method static Item CLAY()
 * @method static Clock CLOCK()
 * @method static Clownfish CLOWNFISH()
 * @method static Coal COAL()
 * @method static Item COAST_ARMOR_TRIM_SMITHING_TEMPLATE()
 * @method static CocoaBeans COCOA_BEANS()
 * @method static Compass COMPASS()
 * @method static CookedChicken COOKED_CHICKEN()
 * @method static CookedFish COOKED_FISH()
 * @method static CookedMutton COOKED_MUTTON()
 * @method static CookedPorkchop COOKED_PORKCHOP()
 * @method static CookedRabbit COOKED_RABBIT()
 * @method static CookedSalmon COOKED_SALMON()
 * @method static Cookie COOKIE()
 * @method static Item COPPER_INGOT()
 * @method static CoralFan CORAL_FAN()
 * @method static SpawnEgg COW_SPAWN_EGG()
 * @method static BannerPattern CREEPER_CHARGE_BANNER_PATTERN()
 * @method static SpawnEgg CREEPER_SPAWN_EGG()
 * @method static ItemBlockWallOrFloor CRIMSON_HANGING_SIGN()
 * @method static ItemBlockWallOrFloor CRIMSON_SIGN()
 * @method static Crossbow CROSSBOW()
 * @method static PotterySherd DANGER_POTTERY_SHERD()
 * @method static Boat DARK_OAK_BOAT()
 * @method static Boat DARK_OAK_CHEST_BOAT()
 * @method static ItemBlockWallOrFloor DARK_OAK_HANGING_SIGN()
 * @method static ItemBlockWallOrFloor DARK_OAK_SIGN()
 * @method static Item DEBUG_STICK()
 * @method static Item DIAMOND()
 * @method static Axe DIAMOND_AXE()
 * @method static Armor DIAMOND_BOOTS()
 * @method static Armor DIAMOND_CHESTPLATE()
 * @method static Armor DIAMOND_HELMET()
 * @method static Hoe DIAMOND_HOE()
 * @method static Armor DIAMOND_LEGGINGS()
 * @method static Pickaxe DIAMOND_PICKAXE()
 * @method static Shovel DIAMOND_SHOVEL()
 * @method static Sword DIAMOND_SWORD()
 * @method static Item DISC_FRAGMENT_5()
 * @method static Item DRAGON_BREATH()
 * @method static DriedKelp DRIED_KELP()
 * @method static Item DUNE_ARMOR_TRIM_SMITHING_TEMPLATE()
 * @method static Dye DYE()
 * @method static Item ECHO_SHARD()
 * @method static Egg EGG()
 * @method static Elytra ELYTRA()
 * @method static Item EMERALD()
 * @method static Map EMPTY_MAP()
 * @method static EnchantedBook ENCHANTED_BOOK()
 * @method static GoldenAppleEnchanted ENCHANTED_GOLDEN_APPLE()
 * @method static SpawnEgg ENDERMAN_SPAWN_EGG()
 * @method static SpawnEgg ENDERMITE_SPAWN_EGG()
 * @method static Item ENDER_EYE()
 * @method static EnderPearl ENDER_PEARL()
 * @method static EndCrystal END_CRYSTAL()
 * @method static ExperienceBottle EXPERIENCE_BOTTLE()
 * @method static PotterySherd EXPLORER_POTTERY_SHERD()
 * @method static Item EYE_ARMOR_TRIM_SMITHING_TEMPLATE()
 * @method static Item FEATHER()
 * @method static Item FERMENTED_SPIDER_EYE()
 * @method static BannerPattern FIELD_MASONED_BANNER_PATTERN()
 * @method static FireworkRocket FIREWORK_ROCKET()
 * @method static FireworkStar FIREWORK_STAR()
 * @method static FireCharge FIRE_CHARGE()
 * @method static FishingRod FISHING_ROD()
 * @method static Item FLINT()
 * @method static FlintSteel FLINT_AND_STEEL()
 * @method static BannerPattern FLOWER_CHARGE_BANNER_PATTERN()
 * @method static Item FLOW_ARMOR_TRIM_SMITHING_TEMPLATE()
 * @method static BannerPattern FLOW_BANNER_PATTERN()
 * @method static PotterySherd FLOW_POTTERY_SHERD()
 * @method static PotterySherd FRIEND_POTTERY_SHERD()
 * @method static Item GHAST_TEAR()
 * @method static GlassBottle GLASS_BOTTLE()
 * @method static Item GLISTERING_MELON()
 * @method static BannerPattern GLOBE_BANNER_PATTERN()
 * @method static Item GLOWSTONE_DUST()
 * @method static GlowBerries GLOW_BERRIES()
 * @method static Item GLOW_INK_SAC()
 * @method static GlowStick GLOW_STICK()
 * @method static GoatHorn GOAT_HORN()
 * @method static GoldenApple GOLDEN_APPLE()
 * @method static Axe GOLDEN_AXE()
 * @method static Armor GOLDEN_BOOTS()
 * @method static GoldenCarrot GOLDEN_CARROT()
 * @method static Armor GOLDEN_CHESTPLATE()
 * @method static Armor GOLDEN_HELMET()
 * @method static Hoe GOLDEN_HOE()
 * @method static Armor GOLDEN_LEGGINGS()
 * @method static Pickaxe GOLDEN_PICKAXE()
 * @method static Shovel GOLDEN_SHOVEL()
 * @method static Sword GOLDEN_SWORD()
 * @method static Item GOLD_INGOT()
 * @method static Item GOLD_NUGGET()
 * @method static Item GUNPOWDER()
 * @method static BannerPattern GUSTER_BANNER_PATTERN()
 * @method static PotterySherd GUSTER_POTTERY_SHERD()
 * @method static PotterySherd HEARTBREAK_POTTERY_SHERD()
 * @method static Item HEART_OF_THE_SEA()
 * @method static PotterySherd HEART_POTTERY_SHERD()
 * @method static Item HONEYCOMB()
 * @method static HoneyBottle HONEY_BOTTLE()
 * @method static Minecart HOPPER_MINECART()
 * @method static Item HOST_ARMOR_TRIM_SMITHING_TEMPLATE()
 * @method static PotterySherd HOWL_POTTERY_SHERD()
 * @method static IceBomb ICE_BOMB()
 * @method static Item INK_SAC()
 * @method static Axe IRON_AXE()
 * @method static Armor IRON_BOOTS()
 * @method static Armor IRON_CHESTPLATE()
 * @method static SpawnEgg IRON_GOLEM_SPAWN_EGG()
 * @method static Armor IRON_HELMET()
 * @method static Hoe IRON_HOE()
 * @method static Item IRON_INGOT()
 * @method static Armor IRON_LEGGINGS()
 * @method static Item IRON_NUGGET()
 * @method static Pickaxe IRON_PICKAXE()
 * @method static Shovel IRON_SHOVEL()
 * @method static Sword IRON_SWORD()
 * @method static Boat JUNGLE_BOAT()
 * @method static Boat JUNGLE_CHEST_BOAT()
 * @method static ItemBlockWallOrFloor JUNGLE_HANGING_SIGN()
 * @method static ItemBlockWallOrFloor JUNGLE_SIGN()
 * @method static Item LAPIS_LAZULI()
 * @method static LiquidBucket LAVA_BUCKET()
 * @method static Lead LEAD()
 * @method static Item LEATHER()
 * @method static Armor LEATHER_BOOTS()
 * @method static Armor LEATHER_CAP()
 * @method static Armor LEATHER_PANTS()
 * @method static Armor LEATHER_TUNIC()
 * @method static LingeringPotion LINGERING_POTION()
 * @method static Mace MACE()
 * @method static Item MAGMA_CREAM()
 * @method static Boat MANGROVE_BOAT()
 * @method static Boat MANGROVE_CHEST_BOAT()
 * @method static ItemBlockWallOrFloor MANGROVE_HANGING_SIGN()
 * @method static ItemBlockWallOrFloor MANGROVE_SIGN()
 * @method static Medicine MEDICINE()
 * @method static Melon MELON()
 * @method static MelonSeeds MELON_SEEDS()
 * @method static MilkBucket MILK_BUCKET()
 * @method static Minecart MINECART()
 * @method static PotterySherd MINER_POTTERY_SHERD()
 * @method static BannerPattern MOJANG_BANNER_PATTERN()
 * @method static SpawnEgg MOOSHROOM_COW_SPAWN_EGG()
 * @method static PotterySherd MOURNER_POTTERY_SHERD()
 * @method static MushroomStew MUSHROOM_STEW()
 * @method static NameTag NAME_TAG()
 * @method static Item NAUTILUS_SHELL()
 * @method static Axe NETHERITE_AXE()
 * @method static Armor NETHERITE_BOOTS()
 * @method static Armor NETHERITE_CHESTPLATE()
 * @method static Armor NETHERITE_HELMET()
 * @method static Hoe NETHERITE_HOE()
 * @method static Item NETHERITE_INGOT()
 * @method static Armor NETHERITE_LEGGINGS()
 * @method static Pickaxe NETHERITE_PICKAXE()
 * @method static Item NETHERITE_SCRAP()
 * @method static Shovel NETHERITE_SHOVEL()
 * @method static Sword NETHERITE_SWORD()
 * @method static Item NETHERITE_UPGRADE_SMITHING_TEMPLATE()
 * @method static Item NETHER_BRICK()
 * @method static Item NETHER_QUARTZ()
 * @method static Item NETHER_SPROUTS()
 * @method static Item NETHER_STAR()
 * @method static Boat OAK_BOAT()
 * @method static Boat OAK_CHEST_BOAT()
 * @method static ItemBlockWallOrFloor OAK_HANGING_SIGN()
 * @method static ItemBlockWallOrFloor OAK_SIGN()
 * @method static OminousBottle OMINOUS_BOTTLE()
 * @method static TrialKey OMINOUS_TRIAL_KEY()
 * @method static PaintingItem PAINTING()
 * @method static Item PAPER()
 * @method static Item PHANTOM_MEMBRANE()
 * @method static BannerPattern PIGLIN_BANNER_PATTERN()
 * @method static SpawnEgg PIG_SPAWN_EGG()
 * @method static PitcherPod PITCHER_POD()
 * @method static PotterySherd PLENTY_POTTERY_SHERD()
 * @method static PoisonousPotato POISONOUS_POTATO()
 * @method static Item POPPED_CHORUS_FRUIT()
 * @method static Potato POTATO()
 * @method static Potion POTION()
 * @method static BlockBucket POWDER_SNOW_BUCKET()
 * @method static Item PRISMARINE_CRYSTALS()
 * @method static Item PRISMARINE_SHARD()
 * @method static PotterySherd PRIZE_POTTERY_SHERD()
 * @method static Pufferfish PUFFERFISH()
 * @method static PumpkinPie PUMPKIN_PIE()
 * @method static PumpkinSeeds PUMPKIN_SEEDS()
 * @method static Item RABBIT_FOOT()
 * @method static Item RABBIT_HIDE()
 * @method static RabbitStew RABBIT_STEW()
 * @method static Item RAISER_ARMOR_TRIM_SMITHING_TEMPLATE()
 * @method static RawBeef RAW_BEEF()
 * @method static RawChicken RAW_CHICKEN()
 * @method static Item RAW_COPPER()
 * @method static RawFish RAW_FISH()
 * @method static Item RAW_GOLD()
 * @method static Item RAW_IRON()
 * @method static RawMutton RAW_MUTTON()
 * @method static RawPorkchop RAW_PORKCHOP()
 * @method static RawRabbit RAW_RABBIT()
 * @method static RawSalmon RAW_SALMON()
 * @method static Record RECORD_11()
 * @method static Record RECORD_13()
 * @method static Record RECORD_5()
 * @method static Record RECORD_BLOCKS()
 * @method static Record RECORD_CAT()
 * @method static Record RECORD_CHIRP()
 * @method static Record RECORD_CREATOR()
 * @method static Record RECORD_CREATOR_MUSIC_BOX()
 * @method static Record RECORD_FAR()
 * @method static Record RECORD_MALL()
 * @method static Record RECORD_MELLOHI()
 * @method static Record RECORD_OTHERSIDE()
 * @method static Record RECORD_PIGSTEP()
 * @method static Record RECORD_PRECIPICE()
 * @method static Record RECORD_RELIC()
 * @method static Record RECORD_STAL()
 * @method static Record RECORD_STRAD()
 * @method static Record RECORD_WAIT()
 * @method static Record RECORD_WARD()
 * @method static Item RECOVERY_COMPASS()
 * @method static Redstone REDSTONE_DUST()
 * @method static Item RIB_ARMOR_TRIM_SMITHING_TEMPLATE()
 * @method static RottenFlesh ROTTEN_FLESH()
 * @method static Saddle SADDLE()
 * @method static PotterySherd SCRAPE_POTTERY_SHERD()
 * @method static Item SCUTE()
 * @method static Item SENTRY_ARMOR_TRIM_SMITHING_TEMPLATE()
 * @method static Item SHAPER_ARMOR_TRIM_SMITHING_TEMPLATE()
 * @method static PotterySherd SHEAF_POTTERY_SHERD()
 * @method static Shears SHEARS()
 * @method static SpawnEgg SHEEP_SPAWN_EGG()
 * @method static PotterySherd SHELTER_POTTERY_SHERD()
 * @method static Item SHULKER_SHELL()
 * @method static Item SILENCE_ARMOR_TRIM_SMITHING_TEMPLATE()
 * @method static BannerPattern SKULL_CHARGE_BANNER_PATTERN()
 * @method static PotterySherd SKULL_POTTERY_SHERD()
 * @method static Item SLIMEBALL()
 * @method static SpawnEgg SLIME_SPAWN_EGG()
 * @method static PotterySherd SNORT_POTTERY_SHERD()
 * @method static Item SNOUT_ARMOR_TRIM_SMITHING_TEMPLATE()
 * @method static Snowball SNOWBALL()
 * @method static SpawnEgg SNOW_GOLEM_SPAWN_EGG()
 * @method static Sparkler SPARKLER()
 * @method static SpiderEye SPIDER_EYE()
 * @method static SpawnEgg SPIDER_SPAWN_EGG()
 * @method static Item SPIRE_ARMOR_TRIM_SMITHING_TEMPLATE()
 * @method static SplashPotion SPLASH_POTION()
 * @method static Boat SPRUCE_BOAT()
 * @method static Boat SPRUCE_CHEST_BOAT()
 * @method static ItemBlockWallOrFloor SPRUCE_HANGING_SIGN()
 * @method static ItemBlockWallOrFloor SPRUCE_SIGN()
 * @method static Spyglass SPYGLASS()
 * @method static Steak STEAK()
 * @method static Stick STICK()
 * @method static Axe STONE_AXE()
 * @method static Hoe STONE_HOE()
 * @method static Pickaxe STONE_PICKAXE()
 * @method static Shovel STONE_SHOVEL()
 * @method static Sword STONE_SWORD()
 * @method static StringItem STRING()
 * @method static Item SUGAR()
 * @method static SuperFertilizer SUPER_FERTILIZER()
 * @method static SuspiciousStew SUSPICIOUS_STEW()
 * @method static SweetBerries SWEET_BERRIES()
 * @method static Item TIDE_ARMOR_TRIM_SMITHING_TEMPLATE()
 * @method static Minecart TNT_MINECART()
 * @method static TorchflowerSeeds TORCHFLOWER_SEEDS()
 * @method static Totem TOTEM()
 * @method static TrialKey TRIAL_KEY()
 * @method static Trident TRIDENT()
 * @method static TurtleHelmet TURTLE_HELMET()
 * @method static Item VEX_ARMOR_TRIM_SMITHING_TEMPLATE()
 * @method static SpawnEgg VILLAGER_SPAWN_EGG()
 * @method static Item WARD_ARMOR_TRIM_SMITHING_TEMPLATE()
 * @method static WarpedFungusOnAStick WARPED_FUNGUS_ON_A_STICK()
 * @method static ItemBlockWallOrFloor WARPED_HANGING_SIGN()
 * @method static ItemBlockWallOrFloor WARPED_SIGN()
 * @method static LiquidBucket WATER_BUCKET()
 * @method static Item WAYFINDER_ARMOR_TRIM_SMITHING_TEMPLATE()
 * @method static Item WHEAT()
 * @method static WheatSeeds WHEAT_SEEDS()
 * @method static Item WILD_ARMOR_TRIM_SMITHING_TEMPLATE()
 * @method static WindCharge WIND_CHARGE()
 * @method static WolfArmor WOLF_ARMOR()
 * @method static Axe WOODEN_AXE()
 * @method static Hoe WOODEN_HOE()
 * @method static Pickaxe WOODEN_PICKAXE()
 * @method static Shovel WOODEN_SHOVEL()
 * @method static Sword WOODEN_SWORD()
 * @method static WritableBook WRITABLE_BOOK()
 * @method static WrittenBook WRITTEN_BOOK()
 * @method static SpawnEgg ZOMBIE_SPAWN_EGG()
 */
final class VanillaItems{
	use CloningRegistryTrait;

	private function __construct(){
		//NOOP
	}

	protected static function register(string $name, Item $item) : void{
		self::_registryRegister($name, $item);
	}

	/**
	 * @return Item[]
	 * @phpstan-return array<string, Item>
	 */
	public static function getAll() : array{
		//phpstan doesn't support generic traits yet :(
		/** @var Item[] $result */
		$result = self::_registryGetAll();
		return $result;
	}

	protected static function setup() : void{
		self::registerArmorItems();
		self::registerSpawnEggs();
		self::registerTierToolItems();
		self::registerSmithingTemplates();

		self::register("air", Blocks::AIR()->asItem()->setCount(0));

		self::register("bamboo_sign", new ItemBlockWallOrFloor(new IID(Ids::BAMBOO_SIGN), Blocks::BAMBOO_SIGN(), Blocks::BAMBOO_WALL_SIGN()));
		self::register("bamboo_hanging_sign", new ItemBlockWallOrFloor(new IID(Ids::BAMBOO_HANGING_SIGN), Blocks::BAMBOO_CEILING_HANGING_SIGN(), Blocks::BAMBOO_WALL_HANGING_SIGN()));
		self::register("acacia_sign", new ItemBlockWallOrFloor(new IID(Ids::ACACIA_SIGN), Blocks::BAMBOO_SIGN(), Blocks::BAMBOO_WALL_SIGN()));
		self::register("acacia_hanging_sign", new ItemBlockWallOrFloor(new IID(Ids::ACACIA_HANGING_SIGN), Blocks::BAMBOO_CEILING_HANGING_SIGN(), Blocks::BAMBOO_WALL_HANGING_SIGN()));
		self::register("amethyst_shard", new Item(new IID(Ids::AMETHYST_SHARD), "Amethyst Shard"));
		self::register("apple", new Apple(new IID(Ids::APPLE), "Apple"));
		self::register("arrow", new Arrow(new IID(Ids::ARROW), "Arrow"));
		self::register("baked_potato", new BakedPotato(new IID(Ids::BAKED_POTATO), "Baked Potato"));
		self::register("bamboo", new Bamboo(new IID(Ids::BAMBOO), "Bamboo"));
		self::register("banner", new Banner(new IID(Ids::BANNER), Blocks::BANNER(), Blocks::WALL_BANNER()));
		self::register("beetroot", new Beetroot(new IID(Ids::BEETROOT), "Beetroot"));
		self::register("beetroot_seeds", new BeetrootSeeds(new IID(Ids::BEETROOT_SEEDS), "Beetroot Seeds"));
		self::register("beetroot_soup", new BeetrootSoup(new IID(Ids::BEETROOT_SOUP), "Beetroot Soup"));
		self::register("birch_sign", new ItemBlockWallOrFloor(new IID(Ids::BIRCH_SIGN), Blocks::BIRCH_SIGN(), Blocks::BIRCH_WALL_SIGN()));
		self::register("birch_hanging_sign", new ItemBlockWallOrFloor(new IID(Ids::BIRCH_HANGING_SIGN), Blocks::BIRCH_CEILING_HANGING_SIGN(), Blocks::BIRCH_WALL_HANGING_SIGN()));
		self::register("blaze_powder", new Item(new IID(Ids::BLAZE_POWDER), "Blaze Powder"));
		self::register("blaze_rod", new BlazeRod(new IID(Ids::BLAZE_ROD), "Blaze Rod"));
		self::register("bleach", new Item(new IID(Ids::BLEACH), "Bleach"));
		self::register("bone", new Item(new IID(Ids::BONE), "Bone"));
		self::register("bone_meal", new Fertilizer(new IID(Ids::BONE_MEAL), "Bone Meal"));
		self::register("book", new Book(new IID(Ids::BOOK), "Book", [EnchantmentTags::ALL]));
		self::register("bow", new Bow(new IID(Ids::BOW), "Bow", [EnchantmentTags::BOW]));
		self::register("bowl", new Bowl(new IID(Ids::BOWL), "Bowl"));
		self::register("bread", new Bread(new IID(Ids::BREAD), "Bread"));
		self::register("brick", new Item(new IID(Ids::BRICK), "Brick"));
		self::register("bucket", new Bucket(new IID(Ids::BUCKET), "Bucket"));
		self::register("carrot", new Carrot(new IID(Ids::CARROT), "Carrot"));
		self::register("charcoal", new Coal(new IID(Ids::CHARCOAL), "Charcoal"));
		self::register("cherry_sign", new ItemBlockWallOrFloor(new IID(Ids::CHERRY_SIGN), Blocks::CHERRY_SIGN(), Blocks::CHERRY_WALL_SIGN()));
		self::register("cherry_hanging_sign", new ItemBlockWallOrFloor(new IID(Ids::CHERRY_HANGING_SIGN), Blocks::CHERRY_CEILING_HANGING_SIGN(), Blocks::CHERRY_WALL_HANGING_SIGN()));
		self::register("chemical_aluminium_oxide", new Item(new IID(Ids::CHEMICAL_ALUMINIUM_OXIDE), "Aluminium Oxide"));
		self::register("chemical_ammonia", new Item(new IID(Ids::CHEMICAL_AMMONIA), "Ammonia"));
		self::register("chemical_barium_sulphate", new Item(new IID(Ids::CHEMICAL_BARIUM_SULPHATE), "Barium Sulphate"));
		self::register("chemical_benzene", new Item(new IID(Ids::CHEMICAL_BENZENE), "Benzene"));
		self::register("chemical_boron_trioxide", new Item(new IID(Ids::CHEMICAL_BORON_TRIOXIDE), "Boron Trioxide"));
		self::register("chemical_calcium_bromide", new Item(new IID(Ids::CHEMICAL_CALCIUM_BROMIDE), "Calcium Bromide"));
		self::register("chemical_calcium_chloride", new Item(new IID(Ids::CHEMICAL_CALCIUM_CHLORIDE), "Calcium Chloride"));
		self::register("chemical_cerium_chloride", new Item(new IID(Ids::CHEMICAL_CERIUM_CHLORIDE), "Cerium Chloride"));
		self::register("chemical_charcoal", new Item(new IID(Ids::CHEMICAL_CHARCOAL), "Charcoal"));
		self::register("chemical_crude_oil", new Item(new IID(Ids::CHEMICAL_CRUDE_OIL), "Crude Oil"));
		self::register("chemical_glue", new Item(new IID(Ids::CHEMICAL_GLUE), "Glue"));
		self::register("chemical_hydrogen_peroxide", new Item(new IID(Ids::CHEMICAL_HYDROGEN_PEROXIDE), "Hydrogen Peroxide"));
		self::register("chemical_hypochlorite", new Item(new IID(Ids::CHEMICAL_HYPOCHLORITE), "Hypochlorite"));
		self::register("chemical_ink", new Item(new IID(Ids::CHEMICAL_INK), "Ink"));
		self::register("chemical_iron_sulphide", new Item(new IID(Ids::CHEMICAL_IRON_SULPHIDE), "Iron Sulphide"));
		self::register("chemical_latex", new Item(new IID(Ids::CHEMICAL_LATEX), "Latex"));
		self::register("chemical_lithium_hydride", new Item(new IID(Ids::CHEMICAL_LITHIUM_HYDRIDE), "Lithium Hydride"));
		self::register("chemical_luminol", new Item(new IID(Ids::CHEMICAL_LUMINOL), "Luminol"));
		self::register("chemical_magnesium_nitrate", new Item(new IID(Ids::CHEMICAL_MAGNESIUM_NITRATE), "Magnesium Nitrate"));
		self::register("chemical_magnesium_oxide", new Item(new IID(Ids::CHEMICAL_MAGNESIUM_OXIDE), "Magnesium Oxide"));
		self::register("chemical_magnesium_salts", new Item(new IID(Ids::CHEMICAL_MAGNESIUM_SALTS), "Magnesium Salts"));
		self::register("chemical_mercuric_chloride", new Item(new IID(Ids::CHEMICAL_MERCURIC_CHLORIDE), "Mercuric Chloride"));
		self::register("chemical_polyethylene", new Item(new IID(Ids::CHEMICAL_POLYETHYLENE), "Polyethylene"));
		self::register("chemical_potassium_chloride", new Item(new IID(Ids::CHEMICAL_POTASSIUM_CHLORIDE), "Potassium Chloride"));
		self::register("chemical_potassium_iodide", new Item(new IID(Ids::CHEMICAL_POTASSIUM_IODIDE), "Potassium Iodide"));
		self::register("chemical_rubbish", new Item(new IID(Ids::CHEMICAL_RUBBISH), "Rubbish"));
		self::register("chemical_salt", new Item(new IID(Ids::CHEMICAL_SALT), "Salt"));
		self::register("chemical_soap", new Item(new IID(Ids::CHEMICAL_SOAP), "Soap"));
		self::register("chemical_sodium_acetate", new Item(new IID(Ids::CHEMICAL_SODIUM_ACETATE), "Sodium Acetate"));
		self::register("chemical_sodium_fluoride", new Item(new IID(Ids::CHEMICAL_SODIUM_FLUORIDE), "Sodium Fluoride"));
		self::register("chemical_sodium_hydride", new Item(new IID(Ids::CHEMICAL_SODIUM_HYDRIDE), "Sodium Hydride"));
		self::register("chemical_sodium_hydroxide", new Item(new IID(Ids::CHEMICAL_SODIUM_HYDROXIDE), "Sodium Hydroxide"));
		self::register("chemical_sodium_hypochlorite", new Item(new IID(Ids::CHEMICAL_SODIUM_HYPOCHLORITE), "Sodium Hypochlorite"));
		self::register("chemical_sodium_oxide", new Item(new IID(Ids::CHEMICAL_SODIUM_OXIDE), "Sodium Oxide"));
		self::register("chemical_sugar", new Item(new IID(Ids::CHEMICAL_SUGAR), "Sugar"));
		self::register("chemical_sulphate", new Item(new IID(Ids::CHEMICAL_SULPHATE), "Sulphate"));
		self::register("chemical_tungsten_chloride", new Item(new IID(Ids::CHEMICAL_TUNGSTEN_CHLORIDE), "Tungsten Chloride"));
		self::register("chemical_water", new Item(new IID(Ids::CHEMICAL_WATER), "Water"));
		self::register("chorus_fruit", new ChorusFruit(new IID(Ids::CHORUS_FRUIT), "Chorus Fruit"));
		self::register("clay", new Item(new IID(Ids::CLAY), "Clay"));
		self::register("clock", new Clock(new IID(Ids::CLOCK), "Clock"));
		self::register("clownfish", new Clownfish(new IID(Ids::CLOWNFISH), "Clownfish"));
		self::register("coal", new Coal(new IID(Ids::COAL), "Coal"));
		self::register("cocoa_beans", new CocoaBeans(new IID(Ids::COCOA_BEANS), "Cocoa Beans"));
		self::register("compass", new Compass(new IID(Ids::COMPASS), "Compass", [EnchantmentTags::COMPASS]));
		self::register("cooked_chicken", new CookedChicken(new IID(Ids::COOKED_CHICKEN), "Cooked Chicken"));
		self::register("cooked_fish", new CookedFish(new IID(Ids::COOKED_FISH), "Cooked Fish"));
		self::register("cooked_mutton", new CookedMutton(new IID(Ids::COOKED_MUTTON), "Cooked Mutton"));
		self::register("cooked_porkchop", new CookedPorkchop(new IID(Ids::COOKED_PORKCHOP), "Cooked Porkchop"));
		self::register("cooked_rabbit", new CookedRabbit(new IID(Ids::COOKED_RABBIT), "Cooked Rabbit"));
		self::register("cooked_salmon", new CookedSalmon(new IID(Ids::COOKED_SALMON), "Cooked Salmon"));
		self::register("cookie", new Cookie(new IID(Ids::COOKIE), "Cookie"));
		self::register("copper_ingot", new Item(new IID(Ids::COPPER_INGOT), "Copper Ingot"));
		self::register("coral_fan", new CoralFan(new IID(Ids::CORAL_FAN)));
		self::register("crimson_sign", new ItemBlockWallOrFloor(new IID(Ids::CRIMSON_SIGN), Blocks::CRIMSON_SIGN(), Blocks::CRIMSON_WALL_SIGN()));
		self::register("crimson_hanging_sign", new ItemBlockWallOrFloor(new IID(Ids::CRIMSON_HANGING_SIGN), Blocks::CRIMSON_CEILING_HANGING_SIGN(), Blocks::CRIMSON_WALL_HANGING_SIGN()));
		self::register("dark_oak_sign", new ItemBlockWallOrFloor(new IID(Ids::DARK_OAK_SIGN), Blocks::DARK_OAK_SIGN(), Blocks::DARK_OAK_WALL_SIGN()));
		self::register("dark_oak_hanging_sign", new ItemBlockWallOrFloor(new IID(Ids::DARK_OAK_HANGING_SIGN), Blocks::DARK_OAK_CEILING_HANGING_SIGN(), Blocks::DARK_OAK_WALL_HANGING_SIGN()));
		self::register("diamond", new Item(new IID(Ids::DIAMOND), "Diamond"));
		self::register("disc_fragment_5", new Item(new IID(Ids::DISC_FRAGMENT_5), "Disc Fragment (5)"));
		self::register("dragon_breath", new Item(new IID(Ids::DRAGON_BREATH), "Dragon's Breath"));
		self::register("dried_kelp", new DriedKelp(new IID(Ids::DRIED_KELP), "Dried Kelp"));
		//TODO: add interface to dye-colour objects
		self::register("dye", new Dye(new IID(Ids::DYE), "Dye"));
		self::register("echo_shard", new Item(new IID(Ids::ECHO_SHARD), "Echo Shard"));
		self::register("egg", new Egg(new IID(Ids::EGG), "Egg"));
		self::register("emerald", new Item(new IID(Ids::EMERALD), "Emerald"));
		self::register("enchanted_book", new EnchantedBook(new IID(Ids::ENCHANTED_BOOK), "Enchanted Book", [EnchantmentTags::ALL]));
		self::register("enchanted_golden_apple", new GoldenAppleEnchanted(new IID(Ids::ENCHANTED_GOLDEN_APPLE), "Enchanted Golden Apple"));
		self::register("ender_eye", new Item(new IID(Ids::ENDER_EYE), "Ender Eye"));
		self::register("end_crystal", new EndCrystal(new IID(Ids::END_CRYSTAL), "End Crystal"));
		self::register("ender_pearl", new EnderPearl(new IID(Ids::ENDER_PEARL), "Ender Pearl"));
		self::register("experience_bottle", new ExperienceBottle(new IID(Ids::EXPERIENCE_BOTTLE), "Bottle o' Enchanting"));
		self::register("feather", new Item(new IID(Ids::FEATHER), "Feather"));
		self::register("fermented_spider_eye", new Item(new IID(Ids::FERMENTED_SPIDER_EYE), "Fermented Spider Eye"));
		self::register("firework_rocket", new FireworkRocket(new IID(Ids::FIREWORK_ROCKET), "Firework Rocket"));
		self::register("firework_star", new FireworkStar(new IID(Ids::FIREWORK_STAR), "Firework Star"));
		self::register("fire_charge", new FireCharge(new IID(Ids::FIRE_CHARGE), "Fire Charge"));
		self::register("fishing_rod", new FishingRod(new IID(Ids::FISHING_ROD), "Fishing Rod", [EnchantmentTags::FISHING_ROD]));
		self::register("flint", new Item(new IID(Ids::FLINT), "Flint"));
		self::register("flint_and_steel", new FlintSteel(new IID(Ids::FLINT_AND_STEEL), "Flint and Steel", [EnchantmentTags::FLINT_AND_STEEL]));
		self::register("ghast_tear", new Item(new IID(Ids::GHAST_TEAR), "Ghast Tear"));
		self::register("glass_bottle", new GlassBottle(new IID(Ids::GLASS_BOTTLE), "Glass Bottle"));
		self::register("glistering_melon", new Item(new IID(Ids::GLISTERING_MELON), "Glistering Melon"));
		self::register("glow_berries", new GlowBerries(new IID(Ids::GLOW_BERRIES), "Glow Berries"));
		self::register("glow_ink_sac", new Item(new IID(Ids::GLOW_INK_SAC), "Glow Ink Sac"));
		self::register("glowstone_dust", new Item(new IID(Ids::GLOWSTONE_DUST), "Glowstone Dust"));
		self::register("goat_horn", new GoatHorn(new IID(Ids::GOAT_HORN), "Goat Horn"));
		self::register("gold_ingot", new Item(new IID(Ids::GOLD_INGOT), "Gold Ingot"));
		self::register("gold_nugget", new Item(new IID(Ids::GOLD_NUGGET), "Gold Nugget"));
		self::register("golden_apple", new GoldenApple(new IID(Ids::GOLDEN_APPLE), "Golden Apple"));
		self::register("golden_carrot", new GoldenCarrot(new IID(Ids::GOLDEN_CARROT), "Golden Carrot"));
		self::register("gunpowder", new Item(new IID(Ids::GUNPOWDER), "Gunpowder"));
		self::register("heart_of_the_sea", new Item(new IID(Ids::HEART_OF_THE_SEA), "Heart of the Sea"));
		self::register("honey_bottle", new HoneyBottle(new IID(Ids::HONEY_BOTTLE), "Honey Bottle"));
		self::register("honeycomb", new Item(new IID(Ids::HONEYCOMB), "Honeycomb"));
		self::register("ink_sac", new Item(new IID(Ids::INK_SAC), "Ink Sac"));
		self::register("iron_ingot", new Item(new IID(Ids::IRON_INGOT), "Iron Ingot"));
		self::register("iron_nugget", new Item(new IID(Ids::IRON_NUGGET), "Iron Nugget"));
		self::register("jungle_sign", new ItemBlockWallOrFloor(new IID(Ids::JUNGLE_SIGN), Blocks::JUNGLE_SIGN(), Blocks::JUNGLE_WALL_SIGN()));
				self::register("jungle_hanging_sign", new ItemBlockWallOrFloor(new IID(Ids::JUNGLE_HANGING_SIGN), Blocks::JUNGLE_CEILING_HANGING_SIGN(), Blocks::JUNGLE_WALL_HANGING_SIGN()));
		self::register("lapis_lazuli", new Item(new IID(Ids::LAPIS_LAZULI), "Lapis Lazuli"));
		self::register("lava_bucket", new LiquidBucket(new IID(Ids::LAVA_BUCKET), "Lava Bucket", Blocks::LAVA()));
		self::register("leather", new Item(new IID(Ids::LEATHER), "Leather"));
		self::register("lingering_potion", new LingeringPotion(new IID(Ids::LINGERING_POTION), "Lingering Potion"));
		self::register("magma_cream", new Item(new IID(Ids::MAGMA_CREAM), "Magma Cream"));
		self::register("mangrove_sign", new ItemBlockWallOrFloor(new IID(Ids::MANGROVE_SIGN), Blocks::MANGROVE_SIGN(), Blocks::MANGROVE_WALL_SIGN()));
				self::register("mangrove_hanging_sign", new ItemBlockWallOrFloor(new IID(Ids::MANGROVE_HANGING_SIGN), Blocks::MANGROVE_CEILING_HANGING_SIGN(), Blocks::MANGROVE_WALL_HANGING_SIGN()));
		self::register("medicine", new Medicine(new IID(Ids::MEDICINE), "Medicine"));
		self::register("melon", new Melon(new IID(Ids::MELON), "Melon"));
		self::register("melon_seeds", new MelonSeeds(new IID(Ids::MELON_SEEDS), "Melon Seeds"));
		self::register("milk_bucket", new MilkBucket(new IID(Ids::MILK_BUCKET), "Milk Bucket"));
		self::register("minecart", new Minecart(new IID(Ids::MINECART), "Minecart"));
		self::register("mushroom_stew", new MushroomStew(new IID(Ids::MUSHROOM_STEW), "Mushroom Stew"));
		self::register("name_tag", new NameTag(new IID(Ids::NAME_TAG), "Name Tag"));
		self::register("nautilus_shell", new Item(new IID(Ids::NAUTILUS_SHELL), "Nautilus Shell"));
		self::register("nether_brick", new Item(new IID(Ids::NETHER_BRICK), "Nether Brick"));
		self::register("nether_quartz", new Item(new IID(Ids::NETHER_QUARTZ), "Nether Quartz"));
		self::register("nether_star", new Item(new IID(Ids::NETHER_STAR), "Nether Star"));
		self::register("netherite_ingot", new class(new IID(Ids::NETHERITE_INGOT), "Netherite Ingot") extends Item{
			public function isFireProof() : bool{ return true; }
		});
		self::register("netherite_scrap", new class(new IID(Ids::NETHERITE_SCRAP), "Netherite Scrap") extends Item{
			public function isFireProof() : bool{ return true; }
		});
		self::register("oak_sign", new ItemBlockWallOrFloor(new IID(Ids::OAK_SIGN), Blocks::OAK_SIGN(), Blocks::OAK_WALL_SIGN()));
				self::register("oak_hanging_sign", new ItemBlockWallOrFloor(new IID(Ids::OAK_HANGING_SIGN), Blocks::OAK_CEILING_HANGING_SIGN(), Blocks::OAK_WALL_HANGING_SIGN()));
		self::register("painting", new PaintingItem(new IID(Ids::PAINTING), "Painting"));
		self::register("paper", new Item(new IID(Ids::PAPER), "Paper"));
		self::register("phantom_membrane", new Item(new IID(Ids::PHANTOM_MEMBRANE), "Phantom Membrane"));
		self::register("pitcher_pod", new PitcherPod(new IID(Ids::PITCHER_POD), "Pitcher Pod"));
		self::register("poisonous_potato", new PoisonousPotato(new IID(Ids::POISONOUS_POTATO), "Poisonous Potato"));
		self::register("popped_chorus_fruit", new Item(new IID(Ids::POPPED_CHORUS_FRUIT), "Popped Chorus Fruit"));
		self::register("potato", new Potato(new IID(Ids::POTATO), "Potato"));
		self::register("potion", new Potion(new IID(Ids::POTION), "Potion"));
		self::register("powder_snow_bucket", new BlockBucket(new IID(Ids::POWDER_SNOW_BUCKET), "Powder Snow Bucket", Blocks::POWDER_SNOW()));
		self::register("prismarine_crystals", new Item(new IID(Ids::PRISMARINE_CRYSTALS), "Prismarine Crystals"));
		self::register("prismarine_shard", new Item(new IID(Ids::PRISMARINE_SHARD), "Prismarine Shard"));
		self::register("pufferfish", new Pufferfish(new IID(Ids::PUFFERFISH), "Pufferfish"));
		self::register("pumpkin_pie", new PumpkinPie(new IID(Ids::PUMPKIN_PIE), "Pumpkin Pie"));
		self::register("pumpkin_seeds", new PumpkinSeeds(new IID(Ids::PUMPKIN_SEEDS), "Pumpkin Seeds"));
		self::register("rabbit_foot", new Item(new IID(Ids::RABBIT_FOOT), "Rabbit's Foot"));
		self::register("rabbit_hide", new Item(new IID(Ids::RABBIT_HIDE), "Rabbit Hide"));
		self::register("rabbit_stew", new RabbitStew(new IID(Ids::RABBIT_STEW), "Rabbit Stew"));
		self::register("raw_beef", new RawBeef(new IID(Ids::RAW_BEEF), "Raw Beef"));
		self::register("raw_chicken", new RawChicken(new IID(Ids::RAW_CHICKEN), "Raw Chicken"));
		self::register("raw_copper", new Item(new IID(Ids::RAW_COPPER), "Raw Copper"));
		self::register("raw_fish", new RawFish(new IID(Ids::RAW_FISH), "Raw Fish"));
		self::register("raw_gold", new Item(new IID(Ids::RAW_GOLD), "Raw Gold"));
		self::register("raw_iron", new Item(new IID(Ids::RAW_IRON), "Raw Iron"));
		self::register("raw_mutton", new RawMutton(new IID(Ids::RAW_MUTTON), "Raw Mutton"));
		self::register("raw_porkchop", new RawPorkchop(new IID(Ids::RAW_PORKCHOP), "Raw Porkchop"));
		self::register("raw_rabbit", new RawRabbit(new IID(Ids::RAW_RABBIT), "Raw Rabbit"));
		self::register("raw_salmon", new RawSalmon(new IID(Ids::RAW_SALMON), "Raw Salmon"));
		self::register("record_11", new Record(new IID(Ids::RECORD_11), RecordType::DISK_11, "Record 11"));
		self::register("record_13", new Record(new IID(Ids::RECORD_13), RecordType::DISK_13, "Record 13"));
		self::register("record_5", new Record(new IID(Ids::RECORD_5), RecordType::DISK_5, "Record 5"));
		self::register("record_blocks", new Record(new IID(Ids::RECORD_BLOCKS), RecordType::DISK_BLOCKS, "Record Blocks"));
		self::register("record_cat", new Record(new IID(Ids::RECORD_CAT), RecordType::DISK_CAT, "Record Cat"));
		self::register("record_chirp", new Record(new IID(Ids::RECORD_CHIRP), RecordType::DISK_CHIRP, "Record Chirp"));
		self::register("record_far", new Record(new IID(Ids::RECORD_FAR), RecordType::DISK_FAR, "Record Far"));
		self::register("record_mall", new Record(new IID(Ids::RECORD_MALL), RecordType::DISK_MALL, "Record Mall"));
		self::register("record_mellohi", new Record(new IID(Ids::RECORD_MELLOHI), RecordType::DISK_MELLOHI, "Record Mellohi"));
		self::register("record_otherside", new Record(new IID(Ids::RECORD_OTHERSIDE), RecordType::DISK_OTHERSIDE, "Record Otherside"));
		self::register("record_pigstep", new Record(new IID(Ids::RECORD_PIGSTEP), RecordType::DISK_PIGSTEP, "Record Pigstep"));
		self::register("record_stal", new Record(new IID(Ids::RECORD_STAL), RecordType::DISK_STAL, "Record Stal"));
		self::register("record_strad", new Record(new IID(Ids::RECORD_STRAD), RecordType::DISK_STRAD, "Record Strad"));
		self::register("record_wait", new Record(new IID(Ids::RECORD_WAIT), RecordType::DISK_WAIT, "Record Wait"));
		self::register("record_ward", new Record(new IID(Ids::RECORD_WARD), RecordType::DISK_WARD, "Record Ward"));
		self::register("recovery_compass", new Item(new IID(Ids::RECOVERY_COMPASS), "Recovery Compass"));
		self::register("redstone_dust", new Redstone(new IID(Ids::REDSTONE_DUST), "Redstone"));
		self::register("rotten_flesh", new RottenFlesh(new IID(Ids::ROTTEN_FLESH), "Rotten Flesh"));
		self::register("scute", new Item(new IID(Ids::SCUTE), "Scute"));
		self::register("shears", new Shears(new IID(Ids::SHEARS), "Shears", [EnchantmentTags::SHEARS]));
		self::register("shulker_shell", new Item(new IID(Ids::SHULKER_SHELL), "Shulker Shell"));
		self::register("slimeball", new Item(new IID(Ids::SLIMEBALL), "Slimeball"));
		self::register("snowball", new Snowball(new IID(Ids::SNOWBALL), "Snowball"));
		self::register("spider_eye", new SpiderEye(new IID(Ids::SPIDER_EYE), "Spider Eye"));
		self::register("splash_potion", new SplashPotion(new IID(Ids::SPLASH_POTION), "Splash Potion"));
		self::register("spruce_sign", new ItemBlockWallOrFloor(new IID(Ids::SPRUCE_SIGN), Blocks::SPRUCE_SIGN(), Blocks::SPRUCE_WALL_SIGN()));
				self::register("spruce_hanging_sign", new ItemBlockWallOrFloor(new IID(Ids::SPRUCE_HANGING_SIGN), Blocks::SPRUCE_CEILING_HANGING_SIGN(), Blocks::SPRUCE_WALL_HANGING_SIGN()));
		self::register("spyglass", new Spyglass(new IID(Ids::SPYGLASS), "Spyglass"));
		self::register("steak", new Steak(new IID(Ids::STEAK), "Steak"));
		self::register("stick", new Stick(new IID(Ids::STICK), "Stick"));
		self::register("string", new StringItem(new IID(Ids::STRING), "String"));
		self::register("sugar", new Item(new IID(Ids::SUGAR), "Sugar"));
		self::register("suspicious_stew", new SuspiciousStew(new IID(Ids::SUSPICIOUS_STEW), "Suspicious Stew"));
		self::register("sweet_berries", new SweetBerries(new IID(Ids::SWEET_BERRIES), "Sweet Berries"));
		self::register("torchflower_seeds", new TorchflowerSeeds(new IID(Ids::TORCHFLOWER_SEEDS), "Torchflower Seeds"));
		self::register("totem", new Totem(new IID(Ids::TOTEM), "Totem of Undying"));
		self::register("warped_sign", new ItemBlockWallOrFloor(new IID(Ids::WARPED_SIGN), Blocks::WARPED_SIGN(), Blocks::WARPED_WALL_SIGN()));
		self::register("warped_hanging_sign", new ItemBlockWallOrFloor(new IID(Ids::WARPED_HANGING_SIGN), Blocks::WARPED_CEILING_HANGING_SIGN(), Blocks::WARPED_WALL_HANGING_SIGN()));
		self::register("water_bucket", new LiquidBucket(new IID(Ids::WATER_BUCKET), "Water Bucket", Blocks::WATER()));
		self::register("wheat", new Item(new IID(Ids::WHEAT), "Wheat"));
		self::register("wheat_seeds", new WheatSeeds(new IID(Ids::WHEAT_SEEDS), "Wheat Seeds"));
		self::register("wind_charge", new WindCharge(new IID(Ids::WIND_CHARGE), "Wind Charge"));
		self::register("writable_book", new WritableBook(new IID(Ids::WRITABLE_BOOK), "Book & Quill"));
		self::register("written_book", new WrittenBook(new IID(Ids::WRITTEN_BOOK), "Written Book"));

		foreach(BoatType::cases() as $type){
			//boat type is static, because different types of wood may have different properties
			self::register(strtolower($type->name) . "_" . ($type === BoatType::BAMBOO ? "raft" : "boat"), new Boat(new IID(match($type){
				BoatType::OAK => Ids::OAK_BOAT,
				BoatType::SPRUCE => Ids::SPRUCE_BOAT,
				BoatType::BIRCH => Ids::BIRCH_BOAT,
				BoatType::JUNGLE => Ids::JUNGLE_BOAT,
				BoatType::ACACIA => Ids::ACACIA_BOAT,
				BoatType::DARK_OAK => Ids::DARK_OAK_BOAT,
				BoatType::MANGROVE => Ids::MANGROVE_BOAT,
				BoatType::CHERRY => Ids::CHERRY_BOAT,
				BoatType::BAMBOO => Ids::BAMBOO_RAFT
			}), $type->getDisplayName() . " " . ($type === BoatType::BAMBOO ? "Raft" : "Boat"), $type));
		}

		foreach(BoatType::cases() as $type){
			//boat type is static, because different types of wood may have different properties
			self::register(strtolower($type->name) . "_chest_" . ($type === BoatType::BAMBOO ? "raft" : "boat"), new Boat(new IID(match($type){
				BoatType::OAK => Ids::OAK_CHEST_BOAT,
				BoatType::SPRUCE => Ids::SPRUCE_CHEST_BOAT,
				BoatType::BIRCH => Ids::BIRCH_CHEST_BOAT,
				BoatType::JUNGLE => Ids::JUNGLE_CHEST_BOAT,
				BoatType::ACACIA => Ids::ACACIA_CHEST_BOAT,
				BoatType::DARK_OAK => Ids::DARK_OAK_CHEST_BOAT,
				BoatType::MANGROVE => Ids::MANGROVE_CHEST_BOAT,
				BoatType::CHERRY => Ids::CHERRY_CHEST_BOAT,
				BoatType::BAMBOO => Ids::BAMBOO_CHEST_RAFT
			}), $type->getDisplayName() . " Chest " . ($type === BoatType::BAMBOO ? "Raft" : "Boat"), $type));
		}
		self::register("nether_sprouts", new Item(new IID(Ids::NETHER_SPROUTS), "Nether Sprouts"));
		self::register("armadillo_shute", new ArmadilloShute(new IID(Ids::ARMADILLO_SHUTE), "Armadillo Shute"));
		self::register("balloon", new Balloon(new IID(Ids::BALLOON), "Balloon"));
		self::register("warped_fungus_on_a_stick", new WarpedFungusOnAStick(new IID(Ids::WARPED_FUNGUS_ON_A_STICK), "Warped Fungus on a Stick"));
		self::register("trident", new Trident(new IID(Ids::TRIDENT), "Trident"));
		self::register("trial_key", new TrialKey(new IID(Ids::TRIAL_KEY), "Trial Key"));
		self::register("ominous_trial_key", new TrialKey(new IID(Ids::OMINOUS_TRIAL_KEY), "Ominous Trial Key"));
		self::register("saddle", new Saddle(new IID(Ids::SADDLE), "Saddle"));
		self::register("super_fertilizer", new SuperFertilizer(new IID(Ids::SUPER_FERTILIZER), "Super Fertilizer"));
		self::register("ominous_bottle", new OminousBottle(new IID(Ids::OMINOUS_BOTTLE), "Ominous Bottle"));
		self::register("record_relic", new Record(new IID(Ids::RECORD_RELIC), RecordType::DISK_OTHERSIDE, "Record Otherside"));
		self::register("record_precipice", new Record(new IID(Ids::RECORD_PRECIPICE), RecordType::DISK_PRECIPICE, "Record Otherside"));
		self::register("record_creator", new Record(new IID(Ids::RECORD_CREATOR), RecordType::DISK_CREATOR, "Record Creator"));
		self::register("record_creator_music_box", new Record(new IID(Ids::RECORD_CREATOR_MUSIC_BOX), RecordType::DISK_CREATOR_MUSIC_BOX, "Record Creator (Music Box)"));
		self::register("mace", new Mace(new IID(Ids::MACE), "Mace"));
		self::register("lead", new Lead(new IID(Ids::LEAD), "Lead"));
		self::register("glow_stick", new GlowStick(new IID(Ids::GLOW_STICK), "Glow Stick"));
		self::register("empty_map", new Map(new IID(Ids::EMPTY_MAP), "Empty Map"));
		self::register("elytra", new Elytra(new IID(Ids::ELYTRA), "Elytra"));
		self::register("debug_stick", new Item(new IID(Ids::DEBUG_STICK), "Debug Stick"));
		self::register("tnt_minecart", new Minecart(new IID(Ids::TNT_MINECART), "TNT Minecart"));
		self::register("hopper_minecart", new Minecart(new IID(Ids::HOPPER_MINECART), "Hopper Minecart"));
		self::register("chest_minecart", new Minecart(new IID(Ids::CHEST_MINECART), "Chest Minecart"));
		self::register("wolf_armor", new WolfArmor(new IID(Ids::WOLF_ARMOR), "Wolf Armor"));
		self::register("ice_bomb", new IceBomb(new IID(Ids::ICE_BOMB), "Ice Bomb"));
		self::register("crossbow", new Crossbow(new IID(Ids::CROSSBOW), "Crossbow"));
		self::register("carrot_on_a_stick", new CarrotOnAStick(new IID(Ids::CARROT_ON_A_STICK), "Carrot on a Stick"));
		self::register("bundle", new Bundle(new IID(Ids::BUNDLE), "Bundle"));
		self::register("brush", new Brush(new IID(Ids::BRUSH), "Brush"));
		self::register("breeze_rod", new BreezeRod(new IID(Ids::BREEZE_ROD), "Breeze Rod"));
		self::register("armor_stand", new ArmorStand(new IID(Ids::ARMOR_STAND), "Armor Stand"));


		foreach(BannerPatternType::cases() as $type){
			self::register(strtolower($type->name) . "_banner_pattern", new BannerPattern(new IID(match($type){
				BannerPatternType::FLOWER_CHARGE => Ids::FLOWER_CHARGE,
				BannerPatternType::CREEPER_CHARGE => Ids::CREEPER_CHARGE,
				BannerPatternType::SKULL_CHARGE => Ids::SKULL_CHARGE,
				BannerPatternType::MOJANG => Ids::MOJANG,
				BannerPatternType::GLOBE => Ids::GLOBE,
				BannerPatternType::PIGLIN => Ids::PIGLIN,
				BannerPatternType::FLOW => Ids::FLOW,
				BannerPatternType::GUSTER => Ids::GUSTER,
				BannerPatternType::FIELD_MASONED => Ids::FIELD_MASONED,
				BannerPatternType::BORDURE_INDENTED => Ids::BORDURE_INDENTED
			}), $type->getDisplayName() . " Banner Pattern", $type));
		}

		foreach(PotterySherdType::cases() as $type){
			self::register(strtolower($type->name) . "_pottery_sherd", new PotterySherd(new IID(match($type){
				PotterySherdType::ANGLER => Ids::ANGLER_POTTERY_SHERD,
				PotterySherdType::ARCHER => Ids::ARCHER_POTTERY_SHERD,
				PotterySherdType::ARMS_UP => Ids::ARMS_UP_POTTERY_SHERD,
				PotterySherdType::BLADE => Ids::BLADE_POTTERY_SHERD,
				PotterySherdType::BREWER => Ids::BREWER_POTTERY_SHERD,
				PotterySherdType::BURN => Ids::BURN_POTTERY_SHERD,
				PotterySherdType::DANGER => Ids::DANGER_POTTERY_SHERD,
				PotterySherdType::EXPLORER => Ids::EXPLORER_POTTERY_SHERD,
				PotterySherdType::FLOW => Ids::FLOW_POTTERY_SHERD,
				PotterySherdType::FRIEND => Ids::FRIEND_POTTERY_SHERD,
				PotterySherdType::GUSTER => Ids::GUSTER_POTTERY_SHERD,
				PotterySherdType::HEART => Ids::HEART_POTTERY_SHERD,
				PotterySherdType::HEARTBREAK => Ids::HEARTBREAK_POTTERY_SHERD,
				PotterySherdType::HOWL => Ids::HOWL_POTTERY_SHERD,
				PotterySherdType::MINER => Ids::MINER_POTTERY_SHERD,
				PotterySherdType::MOURNER => Ids::MOURNER_POTTERY_SHERD,
				PotterySherdType::PLENTY => Ids::PLENTY_POTTERY_SHERD,
				PotterySherdType::PRIZE => Ids::PRIZE_POTTERY_SHERD,
				PotterySherdType::SCRAPE => Ids::SCRAPE_POTTERY_SHERD,
				PotterySherdType::SHEAF => Ids::SHEAF_POTTERY_SHERD,
				PotterySherdType::SHELTER => Ids::SHELTER_POTTERY_SHERD,
				PotterySherdType::SKULL => Ids::SKULL_POTTERY_SHERD,
				PotterySherdType::SNORT => Ids::SNORT_POTTERY_SHERD,
			}), $type->getDisplayName() . " Pottery Sherd", $type));
		}
		self::register("sparkler", new Sparkler(new IID(Ids::SPARKLER), "Sparkler"));
	}



	private static function registerSpawnEggs() : void{
		self::register("bat_spawn_egg", new class(new IID(Ids::BAT_SPAWN_EGG), "Bat Spawn Egg") extends SpawnEgg{
			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
				return new Bat(Location::fromObject($pos, $world, $yaw, $pitch));
			}
		});
		self::register("chicken_spawn_egg", new class(new IID(Ids::CHICKEN_SPAWN_EGG), "Chicken Spawn Egg") extends SpawnEgg{
			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
				return new Chicken(Location::fromObject($pos, $world, $yaw, $pitch));
			}
		});
		self::register("cow_spawn_egg", new class(new IID(Ids::COW_SPAWN_EGG), "Cow Spawn Egg") extends SpawnEgg{
			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
				return new Cow(Location::fromObject($pos, $world, $yaw, $pitch));
			}
		});
		self::register("mooshroom_cow_spawn_egg", new class(new IID(Ids::MOOSHROOM_SPAWN_EGG), "Mooshroom Cow Spawn Egg") extends SpawnEgg{
			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
				return new MooshroomCow(Location::fromObject($pos, $world, $yaw, $pitch));
			}
		});
		self::register("pig_spawn_egg", new class(new IID(Ids::PIG_SPAWN_EGG), "Pig Spawn Egg") extends SpawnEgg{
			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
				return new Pig(Location::fromObject($pos, $world, $yaw, $pitch));
			}
		});
		self::register("sheep_spawn_egg", new class(new IID(Ids::SHEEP_SPAWN_EGG), "Sheep Spawn Egg") extends SpawnEgg{
			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
				return new Sheep(Location::fromObject($pos, $world, $yaw, $pitch));
			}
		});
		self::register("iron_golem_spawn_egg", new class(new IID(Ids::IRON_GOLEM_SPAWN_EGG), "Iron Golem Spawn Egg") extends SpawnEgg{
			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
				return new IronGolem(Location::fromObject($pos, $world, $yaw, $pitch));
			}
		});
		self::register("snow_golem_spawn_egg", new class(new IID(Ids::SNOW_GOLEM_SPAWN_EGG), "Snow Golem Spawn Egg") extends SpawnEgg{
			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
				return new SnowGolem(Location::fromObject($pos, $world, $yaw, $pitch));
			}
		});
		self::register("cave_spider_spawn_egg", new class(new IID(Ids::CAVE_SPIDER_SPAWN_EGG), "Cave Spider Spawn Egg") extends SpawnEgg{
			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
				return new CaveSpider(Location::fromObject($pos, $world, $yaw, $pitch));
			}
		});
		self::register("creeper_spawn_egg", new class(new IID(Ids::CREEPER_SPAWN_EGG), "Creeper Spawn Egg") extends SpawnEgg{
			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
				return new Creeper(Location::fromObject($pos, $world, $yaw, $pitch));
			}
		});
		self::register("enderman_spawn_egg", new class(new IID(Ids::ENDERMAN_SPAWN_EGG), "Enderman Spawn Egg") extends SpawnEgg{
			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
				return new Enderman(Location::fromObject($pos, $world, $yaw, $pitch));
			}
		});
		self::register("endermite_spawn_egg", new class(new IID(Ids::ENDERMITE_SPAWN_EGG), "Endermite Spawn Egg") extends SpawnEgg{
			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
				return new Endermite(Location::fromObject($pos, $world, $yaw, $pitch));
			}
		});
		self::register("slime_spawn_egg", new class(new IID(Ids::SLIME_SPAWN_EGG), "Slime Spawn Egg") extends SpawnEgg{
			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
				return new Slime(Location::fromObject($pos, $world, $yaw, $pitch));
			}
		});
		self::register("spider_spawn_egg", new class(new IID(Ids::SPIDER_SPAWN_EGG), "Spider Spawn Egg") extends SpawnEgg{
			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
				return new Spider(Location::fromObject($pos, $world, $yaw, $pitch));
			}
		});
		self::register("villager_spawn_egg", new class(new IID(Ids::VILLAGER_SPAWN_EGG), "Villager Spawn Egg") extends SpawnEgg{
			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
				return new Villager(Location::fromObject($pos, $world, $yaw, $pitch));
			}
		});
		self::register("zombie_spawn_egg", new class(new IID(Ids::ZOMBIE_SPAWN_EGG), "Zombie Spawn Egg") extends SpawnEgg{
			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
				return new Zombie(Location::fromObject($pos, $world, $yaw, $pitch));
			}
		});


//		self::register("zombie_spawn_egg", new class(new IID(Ids::ZOMBIE_SPAWN_EGG), "Zombie Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Zombie(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//		self::register("squid_spawn_egg", new class(new IID(Ids::SQUID_SPAWN_EGG), "Squid Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Squid(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//		self::register("villager_spawn_egg", new class(new IID(Ids::VILLAGER_SPAWN_EGG), "Villager Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Villager(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//		self::register("allay_spawn_egg", new class(new IID(Ids::ALLAY_SPAWN_EGG), "Allay Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Allay(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("armadillo_spawn_egg", new class(new IID(Ids::ARMADILLO_SPAWN_EGG), "Armadillo Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Armadillo(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("axolotl_spawn_egg", new class(new IID(Ids::AXOLOTL_SPAWN_EGG), "Axolotl Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Axolotl(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("bat_spawn_egg", new class(new IID(Ids::BAT_SPAWN_EGG), "Bat Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Bat(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("camel_spawn_egg", new class(new IID(Ids::CAMEL_SPAWN_EGG), "Camel Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Camel(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("cat_spawn_egg", new class(new IID(Ids::CAT_SPAWN_EGG), "Cat Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Cat(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("chicken_spawn_egg", new class(new IID(Ids::CHICKEN_SPAWN_EGG), "Chicken Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Chicken(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("cod_spawn_egg", new class(new IID(Ids::COD_SPAWN_EGG), "Cod Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Cod(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("cow_spawn_egg", new class(new IID(Ids::COW_SPAWN_EGG), "Cow Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Cow(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("donkey_spawn_egg", new class(new IID(Ids::DONKEY_SPAWN_EGG), "Donkey Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Donkey(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("frog_spawn_egg", new class(new IID(Ids::FROG_SPAWN_EGG), "Frog Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Frog(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("glow_squid_spawn_egg", new class(new IID(Ids::GLOW_SQUID_SPAWN_EGG), "Glow Squid Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new GlowSquid(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("horse_spawn_egg", new class(new IID(Ids::HORSE_SPAWN_EGG), "Horse Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Horse(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("mooshroom_spawn_egg", new class(new IID(Ids::MOOSHROOM_SPAWN_EGG), "Mooshroom Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Mooshroom(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("mule_spawn_egg", new class(new IID(Ids::MULE_SPAWN_EGG), "Mule Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Mule(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("ocelot_spawn_egg", new class(new IID(Ids::OCELOT_SPAWN_EGG), "Ocelot Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Ocelot(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("parrot_spawn_egg", new class(new IID(Ids::PARROT_SPAWN_EGG), "Parrot Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Parrot(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("pig_spawn_egg", new class(new IID(Ids::PIG_SPAWN_EGG), "Pig Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Pig(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("pufferfish_spawn_egg", new class(new IID(Ids::PUFFERFISH_SPAWN_EGG), "Pufferfish Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new EntityPufferfish(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("rabbit_spawn_egg", new class(new IID(Ids::RABBIT_SPAWN_EGG), "Rabbit Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Rabbit(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("salmon_spawn_egg", new class(new IID(Ids::SALMON_SPAWN_EGG), "Salmon Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Salmon(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("sheep_spawn_egg", new class(new IID(Ids::SHEEP_SPAWN_EGG), "Sheep Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Sheep(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("skeleton_horse_spawn_egg", new class(new IID(Ids::SKELETON_HORSE_SPAWN_EGG), "Skeleton Horse Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new SkeletonHorse(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("sniffer_spawn_egg", new class(new IID(Ids::SNIFFER_SPAWN_EGG), "Sniffer Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Sniffer(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("snow_golem_spawn_egg", new class(new IID(Ids::SNOW_GOLEM_SPAWN_EGG), "Snow Golem Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new SnowGolem(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("strider_spawn_egg", new class(new IID(Ids::STRIDER_SPAWN_EGG), "Strider Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Strider(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("tadpole_spawn_egg", new class(new IID(Ids::TADPOLE_SPAWN_EGG), "Tadpole Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Tadpole(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("tropical_fish_spawn_egg", new class(new IID(Ids::TROPICAL_FISH_SPAWN_EGG), "Tropical Fish Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new TropicalFish(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("turtle_spawn_egg", new class(new IID(Ids::TURTLE_SPAWN_EGG), "Turtle Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Turtle(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("wandering_trader_spawn_egg", new class(new IID(Ids::WANDERING_TRADER_SPAWN_EGG), "Wandering Trader Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new WanderingTrader(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("bee_spawn_egg", new class(new IID(Ids::BEE_SPAWN_EGG), "Bee Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Bee(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("cave_spider_spawn_egg", new class(new IID(Ids::CAVE_SPIDER_SPAWN_EGG), "Cave Spider Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new CaveSpider(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("dolphin_spawn_egg", new class(new IID(Ids::DOLPHIN_SPAWN_EGG), "Dolphin Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Dolphin(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("drowned_spawn_egg", new class(new IID(Ids::DROWNED_SPAWN_EGG), "Drowned Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Drowned(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("enderman_spawn_egg", new class(new IID(Ids::ENDERMAN_SPAWN_EGG), "Enderman Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Enderman(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("fox_spawn_egg", new class(new IID(Ids::FOX_SPAWN_EGG), "Fox Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Fox(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("goat_spawn_egg", new class(new IID(Ids::GOAT_SPAWN_EGG), "Goat Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Goat(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("iron_golem_spawn_egg", new class(new IID(Ids::IRON_GOLEM_SPAWN_EGG), "Iron Golem Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new IronGolem(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("llama_spawn_egg", new class(new IID(Ids::LLAMA_SPAWN_EGG), "Llama Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Llama(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("panda_spawn_egg", new class(new IID(Ids::PANDA_SPAWN_EGG), "Panda Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Panda(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("piglin_spawn_egg", new class(new IID(Ids::PIGLIN_SPAWN_EGG), "Piglin Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Piglin(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("polar_bear_spawn_egg", new class(new IID(Ids::POLAR_BEAR_SPAWN_EGG), "Polar Bear Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new PolarBear(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("spider_spawn_egg", new class(new IID(Ids::SPIDER_SPAWN_EGG), "Spider Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Spider(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("trader_llama_spawn_egg", new class(new IID(Ids::TRADER_LLAMA_SPAWN_EGG), "Trader Llama Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new TraderLlama(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("wolf_spawn_egg", new class(new IID(Ids::WOLF_SPAWN_EGG), "Wolf Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Wolf(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("zombified_piglin_spawn_egg", new class(new IID(Ids::ZOMBIFIED_PIGLIN_SPAWN_EGG), "Zombified Piglin Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new ZombifiedPiglin(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("blaze_spawn_egg", new class(new IID(Ids::BLAZE_SPAWN_EGG), "Blaze Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Blaze(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("bogged_spawn_egg", new class(new IID(Ids::BOGGED_SPAWN_EGG), "Bogged Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Bogged(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("breeze_spawn_egg", new class(new IID(Ids::BREEZE_SPAWN_EGG), "Breeze Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Breeze(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("creeper_spawn_egg", new class(new IID(Ids::CREEPER_SPAWN_EGG), "Creeper Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Creeper(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("elder_guardian_spawn_egg", new class(new IID(Ids::ELDER_GUARDIAN_SPAWN_EGG), "Elder Guardian Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new ElderGuardian(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("endermite_spawn_egg", new class(new IID(Ids::ENDERMITE_SPAWN_EGG), "Endermite Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Endermite(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("evoker_spawn_egg", new class(new IID(Ids::EVOKER_SPAWN_EGG), "Evoker Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Evoker(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("ghast_spawn_egg", new class(new IID(Ids::GHAST_SPAWN_EGG), "Ghast Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Ghast(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("guardian_spawn_egg", new class(new IID(Ids::GUARDIAN_SPAWN_EGG), "Guardian Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Guardian(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("hoglin_spawn_egg", new class(new IID(Ids::HOGLIN_SPAWN_EGG), "Hoglin Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Hoglin(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("husk_spawn_egg", new class(new IID(Ids::HUSK_SPAWN_EGG), "Husk Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Husk(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("magma_cube_spawn_egg", new class(new IID(Ids::MAGMA_CUBE_SPAWN_EGG), "Magma Cube Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new MagmaCube(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("phantom_spawn_egg", new class(new IID(Ids::PHANTOM_SPAWN_EGG), "Phantom Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Phantom(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("piglin_brute_spawn_egg", new class(new IID(Ids::PIGLIN_BRUTE_SPAWN_EGG), "Piglin Brute Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new PiglinBrute(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("pillager_spawn_egg", new class(new IID(Ids::PILLAGER_SPAWN_EGG), "Pillager Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Pillager(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("ravager_spawn_egg", new class(new IID(Ids::RAVAGER_SPAWN_EGG), "Ravager Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Ravager(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("shulker_spawn_egg", new class(new IID(Ids::SHULKER_SPAWN_EGG), "Shulker Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Shulker(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("silverfish_spawn_egg", new class(new IID(Ids::SILVERFISH_SPAWN_EGG), "Silverfish Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Silverfish(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("skeleton_spawn_egg", new class(new IID(Ids::SKELETON_SPAWN_EGG), "Skeleton Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Skeleton(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("slime_spawn_egg", new class(new IID(Ids::SLIME_SPAWN_EGG), "Slime Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Slime(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("stray_spawn_egg", new class(new IID(Ids::STRAY_SPAWN_EGG), "Stray Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Stray(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("vex_spawn_egg", new class(new IID(Ids::VEX_SPAWN_EGG), "Vex Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Vex(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("vindicator_spawn_egg", new class(new IID(Ids::VINDICATOR_SPAWN_EGG), "Vindicator Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Vindicator(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("warden_spawn_egg", new class(new IID(Ids::WARDEN_SPAWN_EGG), "Warden Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Warden(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("witch_spawn_egg", new class(new IID(Ids::WITCH_SPAWN_EGG), "Witch Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Witch(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("wither_skeleton_spawn_egg", new class(new IID(Ids::WITHER_SKELETON_SPAWN_EGG), "Wither Skeleton Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new WitherSkeleton(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("zoglin_spawn_egg", new class(new IID(Ids::ZOGLIN_SPAWN_EGG), "Zoglin Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new Zoglin(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
//
//		self::register("zombie_villager_spawn_egg", new class(new IID(Ids::ZOMBIE_VILLAGER_SPAWN_EGG), "Zombie Villager Spawn Egg") extends SpawnEgg{
//			protected function createEntity(World $world, Vector3 $pos, float $yaw, float $pitch) : Entity{
//				return new ZombieVillager(Location::fromObject($pos, $world, $yaw, $pitch));
//			}
//		});
	}

	private static function registerTierToolItems() : void{
		self::register("diamond_axe", new Axe(new IID(Ids::DIAMOND_AXE), "Diamond Axe", ToolTier::DIAMOND, [EnchantmentTags::AXE]));
		self::register("golden_axe", new Axe(new IID(Ids::GOLDEN_AXE), "Golden Axe", ToolTier::GOLD, [EnchantmentTags::AXE]));
		self::register("iron_axe", new Axe(new IID(Ids::IRON_AXE), "Iron Axe", ToolTier::IRON, [EnchantmentTags::AXE]));
		self::register("netherite_axe", new Axe(new IID(Ids::NETHERITE_AXE), "Netherite Axe", ToolTier::NETHERITE, [EnchantmentTags::AXE]));
		self::register("stone_axe", new Axe(new IID(Ids::STONE_AXE), "Stone Axe", ToolTier::STONE, [EnchantmentTags::AXE]));
		self::register("wooden_axe", new Axe(new IID(Ids::WOODEN_AXE), "Wooden Axe", ToolTier::WOOD, [EnchantmentTags::AXE]));
		self::register("diamond_hoe", new Hoe(new IID(Ids::DIAMOND_HOE), "Diamond Hoe", ToolTier::DIAMOND, [EnchantmentTags::HOE]));
		self::register("golden_hoe", new Hoe(new IID(Ids::GOLDEN_HOE), "Golden Hoe", ToolTier::GOLD, [EnchantmentTags::HOE]));
		self::register("iron_hoe", new Hoe(new IID(Ids::IRON_HOE), "Iron Hoe", ToolTier::IRON, [EnchantmentTags::HOE]));
		self::register("netherite_hoe", new Hoe(new IID(Ids::NETHERITE_HOE), "Netherite Hoe", ToolTier::NETHERITE, [EnchantmentTags::HOE]));
		self::register("stone_hoe", new Hoe(new IID(Ids::STONE_HOE), "Stone Hoe", ToolTier::STONE, [EnchantmentTags::HOE]));
		self::register("wooden_hoe", new Hoe(new IID(Ids::WOODEN_HOE), "Wooden Hoe", ToolTier::WOOD, [EnchantmentTags::HOE]));
		self::register("diamond_pickaxe", new Pickaxe(new IID(Ids::DIAMOND_PICKAXE), "Diamond Pickaxe", ToolTier::DIAMOND, [EnchantmentTags::PICKAXE]));
		self::register("golden_pickaxe", new Pickaxe(new IID(Ids::GOLDEN_PICKAXE), "Golden Pickaxe", ToolTier::GOLD, [EnchantmentTags::PICKAXE]));
		self::register("iron_pickaxe", new Pickaxe(new IID(Ids::IRON_PICKAXE), "Iron Pickaxe", ToolTier::IRON, [EnchantmentTags::PICKAXE]));
		self::register("netherite_pickaxe", new Pickaxe(new IID(Ids::NETHERITE_PICKAXE), "Netherite Pickaxe", ToolTier::NETHERITE, [EnchantmentTags::PICKAXE]));
		self::register("stone_pickaxe", new Pickaxe(new IID(Ids::STONE_PICKAXE), "Stone Pickaxe", ToolTier::STONE, [EnchantmentTags::PICKAXE]));
		self::register("wooden_pickaxe", new Pickaxe(new IID(Ids::WOODEN_PICKAXE), "Wooden Pickaxe", ToolTier::WOOD, [EnchantmentTags::PICKAXE]));
		self::register("diamond_shovel", new Shovel(new IID(Ids::DIAMOND_SHOVEL), "Diamond Shovel", ToolTier::DIAMOND, [EnchantmentTags::SHOVEL]));
		self::register("golden_shovel", new Shovel(new IID(Ids::GOLDEN_SHOVEL), "Golden Shovel", ToolTier::GOLD, [EnchantmentTags::SHOVEL]));
		self::register("iron_shovel", new Shovel(new IID(Ids::IRON_SHOVEL), "Iron Shovel", ToolTier::IRON, [EnchantmentTags::SHOVEL]));
		self::register("netherite_shovel", new Shovel(new IID(Ids::NETHERITE_SHOVEL), "Netherite Shovel", ToolTier::NETHERITE, [EnchantmentTags::SHOVEL]));
		self::register("stone_shovel", new Shovel(new IID(Ids::STONE_SHOVEL), "Stone Shovel", ToolTier::STONE, [EnchantmentTags::SHOVEL]));
		self::register("wooden_shovel", new Shovel(new IID(Ids::WOODEN_SHOVEL), "Wooden Shovel", ToolTier::WOOD, [EnchantmentTags::SHOVEL]));
		self::register("diamond_sword", new Sword(new IID(Ids::DIAMOND_SWORD), "Diamond Sword", ToolTier::DIAMOND, [EnchantmentTags::SWORD]));
		self::register("golden_sword", new Sword(new IID(Ids::GOLDEN_SWORD), "Golden Sword", ToolTier::GOLD, [EnchantmentTags::SWORD]));
		self::register("iron_sword", new Sword(new IID(Ids::IRON_SWORD), "Iron Sword", ToolTier::IRON, [EnchantmentTags::SWORD]));
		self::register("netherite_sword", new Sword(new IID(Ids::NETHERITE_SWORD), "Netherite Sword", ToolTier::NETHERITE, [EnchantmentTags::SWORD]));
		self::register("stone_sword", new Sword(new IID(Ids::STONE_SWORD), "Stone Sword", ToolTier::STONE, [EnchantmentTags::SWORD]));
		self::register("wooden_sword", new Sword(new IID(Ids::WOODEN_SWORD), "Wooden Sword", ToolTier::WOOD, [EnchantmentTags::SWORD]));
	}

	private static function registerArmorItems() : void{
		self::register("chainmail_boots", new Armor(new IID(Ids::CHAINMAIL_BOOTS), "Chainmail Boots", new ArmorTypeInfo(1, 196, ArmorInventory::SLOT_FEET, material: ArmorMaterials::CHAINMAIL()), [EnchantmentTags::BOOTS]));
		self::register("diamond_boots", new Armor(new IID(Ids::DIAMOND_BOOTS), "Diamond Boots", new ArmorTypeInfo(3, 430, ArmorInventory::SLOT_FEET, 2, material: ArmorMaterials::DIAMOND()), [EnchantmentTags::BOOTS]));
		self::register("golden_boots", new Armor(new IID(Ids::GOLDEN_BOOTS), "Golden Boots", new ArmorTypeInfo(1, 92, ArmorInventory::SLOT_FEET, material: ArmorMaterials::GOLD()), [EnchantmentTags::BOOTS]));
		self::register("iron_boots", new Armor(new IID(Ids::IRON_BOOTS), "Iron Boots", new ArmorTypeInfo(2, 196, ArmorInventory::SLOT_FEET, material: ArmorMaterials::IRON()), [EnchantmentTags::BOOTS]));
		self::register("leather_boots", new Armor(new IID(Ids::LEATHER_BOOTS), "Leather Boots", new ArmorTypeInfo(1, 66, ArmorInventory::SLOT_FEET, material: ArmorMaterials::LEATHER()), [EnchantmentTags::BOOTS]));
		self::register("netherite_boots", new Armor(new IID(Ids::NETHERITE_BOOTS), "Netherite Boots", new ArmorTypeInfo(3, 482, ArmorInventory::SLOT_FEET, 3, true, material: ArmorMaterials::NETHERITE()), [EnchantmentTags::BOOTS]));

		self::register("chainmail_chestplate", new Armor(new IID(Ids::CHAINMAIL_CHESTPLATE), "Chainmail Chestplate", new ArmorTypeInfo(5, 241, ArmorInventory::SLOT_CHEST, material: ArmorMaterials::CHAINMAIL()), [EnchantmentTags::CHESTPLATE]));
		self::register("diamond_chestplate", new Armor(new IID(Ids::DIAMOND_CHESTPLATE), "Diamond Chestplate", new ArmorTypeInfo(8, 529, ArmorInventory::SLOT_CHEST, 2, material: ArmorMaterials::DIAMOND()), [EnchantmentTags::CHESTPLATE]));
		self::register("golden_chestplate", new Armor(new IID(Ids::GOLDEN_CHESTPLATE), "Golden Chestplate", new ArmorTypeInfo(5, 113, ArmorInventory::SLOT_CHEST, material: ArmorMaterials::GOLD()), [EnchantmentTags::CHESTPLATE]));
		self::register("iron_chestplate", new Armor(new IID(Ids::IRON_CHESTPLATE), "Iron Chestplate", new ArmorTypeInfo(6, 241, ArmorInventory::SLOT_CHEST, material: ArmorMaterials::IRON()), [EnchantmentTags::CHESTPLATE]));
		self::register("leather_tunic", new Armor(new IID(Ids::LEATHER_TUNIC), "Leather Tunic", new ArmorTypeInfo(3, 81, ArmorInventory::SLOT_CHEST, material: ArmorMaterials::LEATHER()), [EnchantmentTags::CHESTPLATE]));
		self::register("netherite_chestplate", new Armor(new IID(Ids::NETHERITE_CHESTPLATE), "Netherite Chestplate", new ArmorTypeInfo(8, 593, ArmorInventory::SLOT_CHEST, 3, true, material: ArmorMaterials::NETHERITE()), [EnchantmentTags::CHESTPLATE]));

		self::register("chainmail_helmet", new Armor(new IID(Ids::CHAINMAIL_HELMET), "Chainmail Helmet", new ArmorTypeInfo(2, 166, ArmorInventory::SLOT_HEAD, material: ArmorMaterials::CHAINMAIL()), [EnchantmentTags::HELMET]));
		self::register("diamond_helmet", new Armor(new IID(Ids::DIAMOND_HELMET), "Diamond Helmet", new ArmorTypeInfo(3, 364, ArmorInventory::SLOT_HEAD, 2, material: ArmorMaterials::DIAMOND()), [EnchantmentTags::HELMET]));
		self::register("golden_helmet", new Armor(new IID(Ids::GOLDEN_HELMET), "Golden Helmet", new ArmorTypeInfo(2, 78, ArmorInventory::SLOT_HEAD, material: ArmorMaterials::GOLD()), [EnchantmentTags::HELMET]));
		self::register("iron_helmet", new Armor(new IID(Ids::IRON_HELMET), "Iron Helmet", new ArmorTypeInfo(2, 166, ArmorInventory::SLOT_HEAD, material: ArmorMaterials::IRON()), [EnchantmentTags::HELMET]));
		self::register("leather_cap", new Armor(new IID(Ids::LEATHER_CAP), "Leather Cap", new ArmorTypeInfo(1, 56, ArmorInventory::SLOT_HEAD, material: ArmorMaterials::LEATHER()), [EnchantmentTags::HELMET]));
		self::register("netherite_helmet", new Armor(new IID(Ids::NETHERITE_HELMET), "Netherite Helmet", new ArmorTypeInfo(3, 408, ArmorInventory::SLOT_HEAD, 3, true, material: ArmorMaterials::NETHERITE()), [EnchantmentTags::HELMET]));
		self::register("turtle_helmet", new TurtleHelmet(new IID(Ids::TURTLE_HELMET), "Turtle Shell", new ArmorTypeInfo(2, 276, ArmorInventory::SLOT_HEAD, material: ArmorMaterials::TURTLE()), [EnchantmentTags::HELMET]));

		self::register("chainmail_leggings", new Armor(new IID(Ids::CHAINMAIL_LEGGINGS), "Chainmail Leggings", new ArmorTypeInfo(4, 226, ArmorInventory::SLOT_LEGS, material: ArmorMaterials::CHAINMAIL()), [EnchantmentTags::LEGGINGS]));
		self::register("diamond_leggings", new Armor(new IID(Ids::DIAMOND_LEGGINGS), "Diamond Leggings", new ArmorTypeInfo(6, 496, ArmorInventory::SLOT_LEGS, 2, material: ArmorMaterials::DIAMOND()), [EnchantmentTags::LEGGINGS]));
		self::register("golden_leggings", new Armor(new IID(Ids::GOLDEN_LEGGINGS), "Golden Leggings", new ArmorTypeInfo(3, 106, ArmorInventory::SLOT_LEGS, material: ArmorMaterials::GOLD()), [EnchantmentTags::LEGGINGS]));
		self::register("iron_leggings", new Armor(new IID(Ids::IRON_LEGGINGS), "Iron Leggings", new ArmorTypeInfo(5, 226, ArmorInventory::SLOT_LEGS, material: ArmorMaterials::IRON()), [EnchantmentTags::LEGGINGS]));
		self::register("leather_pants", new Armor(new IID(Ids::LEATHER_PANTS), "Leather Pants", new ArmorTypeInfo(2, 76, ArmorInventory::SLOT_LEGS, material: ArmorMaterials::LEATHER()), [EnchantmentTags::LEGGINGS]));
		self::register("netherite_leggings", new Armor(new IID(Ids::NETHERITE_LEGGINGS), "Netherite Leggings", new ArmorTypeInfo(6, 556, ArmorInventory::SLOT_LEGS, 3, true, material: ArmorMaterials::NETHERITE()), [EnchantmentTags::LEGGINGS]));
	}

	private static function registerSmithingTemplates() : void{
		self::register("netherite_upgrade_smithing_template", new Item(new IID(Ids::NETHERITE_UPGRADE_SMITHING_TEMPLATE), "Netherite Upgrade Smithing Template"));
		self::register("coast_armor_trim_smithing_template", new Item(new IID(Ids::COAST_ARMOR_TRIM_SMITHING_TEMPLATE), "Coast Armor Trim Smithing Template"));
		self::register("dune_armor_trim_smithing_template", new Item(new IID(Ids::DUNE_ARMOR_TRIM_SMITHING_TEMPLATE), "Dune Armor Trim Smithing Template"));
		self::register("eye_armor_trim_smithing_template", new Item(new IID(Ids::EYE_ARMOR_TRIM_SMITHING_TEMPLATE), "Eye Armor Trim Smithing Template"));
		self::register("host_armor_trim_smithing_template", new Item(new IID(Ids::HOST_ARMOR_TRIM_SMITHING_TEMPLATE), "Host Armor Trim Smithing Template"));
		self::register("raiser_armor_trim_smithing_template", new Item(new IID(Ids::RAISER_ARMOR_TRIM_SMITHING_TEMPLATE), "Raiser Armor Trim Smithing Template"));
		self::register("rib_armor_trim_smithing_template", new Item(new IID(Ids::RIB_ARMOR_TRIM_SMITHING_TEMPLATE), "Rib Armor Trim Smithing Template"));
		self::register("sentry_armor_trim_smithing_template", new Item(new IID(Ids::SENTRY_ARMOR_TRIM_SMITHING_TEMPLATE), "Sentry Armor Trim Smithing Template"));
		self::register("shaper_armor_trim_smithing_template", new Item(new IID(Ids::SHAPER_ARMOR_TRIM_SMITHING_TEMPLATE), "Shaper Armor Trim Smithing Template"));
		self::register("silence_armor_trim_smithing_template", new Item(new IID(Ids::SILENCE_ARMOR_TRIM_SMITHING_TEMPLATE), "Silence Armor Trim Smithing Template"));
		self::register("snout_armor_trim_smithing_template", new Item(new IID(Ids::SNOUT_ARMOR_TRIM_SMITHING_TEMPLATE), "Snout Armor Trim Smithing Template"));
		self::register("spire_armor_trim_smithing_template", new Item(new IID(Ids::SPIRE_ARMOR_TRIM_SMITHING_TEMPLATE), "Spire Armor Trim Smithing Template"));
		self::register("tide_armor_trim_smithing_template", new Item(new IID(Ids::TIDE_ARMOR_TRIM_SMITHING_TEMPLATE), "Tide Armor Trim Smithing Template"));
		self::register("vex_armor_trim_smithing_template", new Item(new IID(Ids::VEX_ARMOR_TRIM_SMITHING_TEMPLATE), "Vex Armor Trim Smithing Template"));
		self::register("ward_armor_trim_smithing_template", new Item(new IID(Ids::WARD_ARMOR_TRIM_SMITHING_TEMPLATE), "Ward Armor Trim Smithing Template"));
		self::register("wayfinder_armor_trim_smithing_template", new Item(new IID(Ids::WAYFINDER_ARMOR_TRIM_SMITHING_TEMPLATE), "Wayfinder Armor Trim Smithing Template"));
		self::register("wild_armor_trim_smithing_template", new Item(new IID(Ids::WILD_ARMOR_TRIM_SMITHING_TEMPLATE), "Wild Armor Trim Smithing Template"));
		self::register("bolt_armor_trim_smithing_template", new Item(new IID(Ids::BOLT_ARMOR_TRIM_SMITHING_TEMPLATE), "Bolt Armor Trim Smithing Template"));
		self::register("flow_armor_trim_smithing_template", new Item(new IID(Ids::FLOW_ARMOR_TRIM_SMITHING_TEMPLATE), "Flow Armor Trim Smithing Template"));
	}

}
