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

namespace pocketmine\data\bedrock\item;

use pocketmine\block\Bed;
use pocketmine\block\Block;
use pocketmine\block\MobHead;
use pocketmine\block\CopperDoor;
use pocketmine\block\utils\CopperOxidation;
use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks as Blocks;
use pocketmine\data\bedrock\CompoundTypeIds;
use pocketmine\data\bedrock\DyeColorIdMap;
use pocketmine\data\bedrock\GoatHornTypeIdMap;
use pocketmine\data\bedrock\item\ItemTypeNames as Ids;
use pocketmine\data\bedrock\item\SavedItemData as Data;
use pocketmine\data\bedrock\MedicineTypeIdMap;
use pocketmine\data\bedrock\MobHeadTypeIdMap;
use pocketmine\data\bedrock\PotionTypeIdMap;
use pocketmine\data\bedrock\SuspiciousStewTypeIdMap;
use pocketmine\item\Arrow;
use pocketmine\item\Balloon;
use pocketmine\item\Banner;
use pocketmine\item\Dye;
use pocketmine\item\FireworkStar;
use pocketmine\item\GlowStick;
use pocketmine\item\GoatHorn;
use pocketmine\item\Item;
use pocketmine\item\LingeringPotion;
use pocketmine\item\Medicine;
use pocketmine\item\Potion;
use pocketmine\item\SplashPotion;
use pocketmine\item\SuspiciousStew;
use pocketmine\item\VanillaItems as Items;

final class ItemSerializerDeserializerRegistrar{

	public function __construct(
		private ?ItemDeserializer $deserializer,
		private ?ItemSerializer $serializer
	){
		$this->register1to1BlockMappings();
		$this->register1to1ItemMappings();
		$this->register1to1BlockWithMetaMappings();
		$this->register1to1ItemWithMetaMappings();
		$this->register1ToNItemMappings();
		$this->registerMiscBlockMappings();
		$this->registerMiscItemMappings();
	}

	public function map1to1Item(string $id, Item $item) : void{
		$this->deserializer?->map($id, fn() => clone $item);
		$this->serializer?->map($item, fn() => new Data($id));
	}

	/**
	 * @phpstan-template TItem of Item
	 * @phpstan-param TItem                       $item
	 * @phpstan-param \Closure(TItem, int) : void $deserializeMeta
	 * @phpstan-param \Closure(TItem) : int       $serializeMeta
	 */
	public function map1to1ItemWithMeta(string $id, Item $item, \Closure $deserializeMeta, \Closure $serializeMeta) : void{
		$this->deserializer?->map($id, function(Data $data) use ($item, $deserializeMeta) : Item{
			$result = clone $item;
			$deserializeMeta($result, $data->getMeta());
			return $result;
		});
		$this->serializer?->map($item, function(Item $item) use ($id, $serializeMeta) : Data{
			/** @phpstan-var TItem $item */
			$meta = $serializeMeta($item);
			return new Data($id, $meta);
		});
	}

	public function map1to1Block(string $id, Block $block) : void{
		$this->deserializer?->mapBlock($id, fn() => $block);
		$this->serializer?->mapBlock($block, fn() => new Data($id));
	}

	/**
	 * @phpstan-template TBlock of Block
	 * @phpstan-param TBlock                       $block
	 * @phpstan-param \Closure(TBlock, int) : void $deserializeMeta
	 * @phpstan-param \Closure(TBlock) : int       $serializeMeta
	 */
	public function map1to1BlockWithMeta(string $id, Block $block, \Closure $deserializeMeta, \Closure $serializeMeta) : void{
		$this->deserializer?->mapBlock($id, function(Data $data) use ($block, $deserializeMeta) : Block{
			$result = clone $block;
			$deserializeMeta($result, $data->getMeta());
			return $result;
		});
		$this->serializer?->mapBlock($block, function(Block $block) use ($id, $serializeMeta) : Data{
			$meta = $serializeMeta($block);
			return new Data($id, $meta);
		});
	}

	/**
	 * @param Item[] $items
	 * @phpstan-param array<int, Item> $items
	 */
	public function map1ToNItem(string $id, array $items) : void{
		$this->deserializer?->map($id, function(Data $data) use ($items) : Item{
			$result = $items[$data->getMeta()] ?? null;
			if($result === null){
				throw new ItemTypeDeserializeException("Unhandled meta value " . $data->getMeta() . " for item ID " . $data->getName());
			}
			return clone $result;
		});
		foreach($items as $meta => $item){
			$this->serializer?->map($item, fn() => new Data($id, $meta));
		}
	}

	/**
	 * Registers mappings for item IDs which directly correspond to PocketMine-MP blockitems.
	 * Mappings here are only necessary when the item has a dedicated item ID; in these cases, the blockstate is not
	 * included in the itemstack, and the item ID may be different from the block ID.
	 */
	private function register1to1BlockMappings() : void{
		$this->map1to1Block(Ids::ACACIA_DOOR, Blocks::ACACIA_DOOR());
		$this->map1to1Block(Ids::BIRCH_DOOR, Blocks::BIRCH_DOOR());
		$this->map1to1Block(Ids::BREWING_STAND, Blocks::BREWING_STAND());
		$this->map1to1Block(Ids::CAKE, Blocks::CAKE());
		$this->map1to1Block(Ids::CAMPFIRE, Blocks::CAMPFIRE());
		$this->map1to1Block(Ids::CAULDRON, Blocks::CAULDRON());
		$this->map1to1Block(Ids::CHAIN, Blocks::CHAIN());
		$this->map1to1Block(Ids::CHERRY_DOOR, Blocks::CHERRY_DOOR());
		$this->map1to1Block(Ids::COMPARATOR, Blocks::REDSTONE_COMPARATOR());
		$this->map1to1Block(Ids::CRIMSON_DOOR, Blocks::CRIMSON_DOOR());
		$this->map1to1Block(Ids::DARK_OAK_DOOR, Blocks::DARK_OAK_DOOR());
		$this->map1to1Block(Ids::FLOWER_POT, Blocks::FLOWER_POT());
		$this->map1to1Block(Ids::FRAME, Blocks::ITEM_FRAME());
		$this->map1to1Block(Ids::GLOW_FRAME, Blocks::GLOWING_ITEM_FRAME());
		$this->map1to1Block(Ids::HOPPER, Blocks::HOPPER());
		$this->map1to1Block(Ids::IRON_DOOR, Blocks::IRON_DOOR());
		$this->map1to1Block(Ids::JUNGLE_DOOR, Blocks::JUNGLE_DOOR());
		$this->map1to1Block(Ids::BAMBOO_DOOR, Blocks::BAMBOO_DOOR());
		$this->map1to1Block(Ids::MANGROVE_DOOR, Blocks::MANGROVE_DOOR());
		$this->map1to1Block(Ids::NETHER_WART, Blocks::NETHER_WART());
		$this->map1to1Block(Ids::REPEATER, Blocks::REDSTONE_REPEATER());
		$this->map1to1Block(Ids::SOUL_CAMPFIRE, Blocks::SOUL_CAMPFIRE());
		$this->map1to1Block(Ids::SPRUCE_DOOR, Blocks::SPRUCE_DOOR());
		$this->map1to1Block(Ids::SUGAR_CANE, Blocks::SUGARCANE());
		$this->map1to1Block(Ids::WARPED_DOOR, Blocks::WARPED_DOOR());
		$this->map1to1Block(Ids::WOODEN_DOOR, Blocks::OAK_DOOR());
		$this->map1to1Block(Ids::NETHER_SPROUTS, Blocks::NETHER_SPROUTS());
	}

	/**
	 * Registers mappings for item IDs which directly correspond to PocketMine-MP items.
	 */
	private function register1to1ItemMappings() : void{
		$this->map1to1Item(Ids::BAMBOO_HANGING_SIGN, Items::BAMBOO_HANGING_SIGN());
		$this->map1to1Item(Ids::BAMBOO_SIGN, Items::BAMBOO_SIGN());
		$this->map1to1Item(Ids::ACACIA_BOAT, Items::ACACIA_BOAT());
		$this->map1to1Item(Ids::ACACIA_HANGING_SIGN, Items::ACACIA_HANGING_SIGN());
		$this->map1to1Item(Ids::ACACIA_SIGN, Items::ACACIA_SIGN());
		$this->map1to1Item(Ids::AMETHYST_SHARD, Items::AMETHYST_SHARD());
		$this->map1to1Item(Ids::APPLE, Items::APPLE());
		$this->map1to1Item(Ids::BAKED_POTATO, Items::BAKED_POTATO());
		$this->map1to1Item(Ids::BEEF, Items::RAW_BEEF());
		$this->map1to1Item(Ids::BEETROOT, Items::BEETROOT());
		$this->map1to1Item(Ids::BEETROOT_SEEDS, Items::BEETROOT_SEEDS());
		$this->map1to1Item(Ids::BEETROOT_SOUP, Items::BEETROOT_SOUP());
		$this->map1to1Item(Ids::BIRCH_BOAT, Items::BIRCH_BOAT());
		$this->map1to1Item(Ids::BIRCH_HANGING_SIGN, Items::BIRCH_HANGING_SIGN());
		$this->map1to1Item(Ids::BIRCH_SIGN, Items::BIRCH_SIGN());
		$this->map1to1Item(Ids::BLAZE_POWDER, Items::BLAZE_POWDER());
		$this->map1to1Item(Ids::BLAZE_ROD, Items::BLAZE_ROD());
		$this->map1to1Item(Ids::BLEACH, Items::BLEACH());
		$this->map1to1Item(Ids::BONE, Items::BONE());
		$this->map1to1Item(Ids::BONE_MEAL, Items::BONE_MEAL());
		$this->map1to1Item(Ids::BOOK, Items::BOOK());
		$this->map1to1Item(Ids::BOW, Items::BOW());
		$this->map1to1Item(Ids::BOWL, Items::BOWL());
		$this->map1to1Item(Ids::BREAD, Items::BREAD());
		$this->map1to1Item(Ids::BRICK, Items::BRICK());
		$this->map1to1Item(Ids::BUCKET, Items::BUCKET());
		$this->map1to1Item(Ids::CARROT, Items::CARROT());
		$this->map1to1Item(Ids::CHAINMAIL_BOOTS, Items::CHAINMAIL_BOOTS());
		$this->map1to1Item(Ids::CHAINMAIL_CHESTPLATE, Items::CHAINMAIL_CHESTPLATE());
		$this->map1to1Item(Ids::CHAINMAIL_HELMET, Items::CHAINMAIL_HELMET());
		$this->map1to1Item(Ids::CHAINMAIL_LEGGINGS, Items::CHAINMAIL_LEGGINGS());
		$this->map1to1Item(Ids::CHARCOAL, Items::CHARCOAL());
		$this->map1to1Item(Ids::CHERRY_HANGING_SIGN, Items::CHERRY_HANGING_SIGN());
		$this->map1to1Item(Ids::CHERRY_SIGN, Items::CHERRY_SIGN());
		$this->map1to1Item(Ids::CHICKEN, Items::RAW_CHICKEN());
		$this->map1to1Item(Ids::CHORUS_FRUIT, Items::CHORUS_FRUIT());
		$this->map1to1Item(Ids::CLAY_BALL, Items::CLAY());
		$this->map1to1Item(Ids::CLOCK, Items::CLOCK());
		$this->map1to1Item(Ids::COAL, Items::COAL());
		$this->map1to1Item(Ids::COAST_ARMOR_TRIM_SMITHING_TEMPLATE, Items::COAST_ARMOR_TRIM_SMITHING_TEMPLATE());
		$this->map1to1Item(Ids::COCOA_BEANS, Items::COCOA_BEANS());
		$this->map1to1Item(Ids::COD, Items::RAW_FISH());
		$this->map1to1Item(Ids::COMPASS, Items::COMPASS());
		$this->map1to1Item(Ids::COOKED_BEEF, Items::STEAK());
		$this->map1to1Item(Ids::COOKED_CHICKEN, Items::COOKED_CHICKEN());
		$this->map1to1Item(Ids::COOKED_COD, Items::COOKED_FISH());
		$this->map1to1Item(Ids::COOKED_MUTTON, Items::COOKED_MUTTON());
		$this->map1to1Item(Ids::COOKED_PORKCHOP, Items::COOKED_PORKCHOP());
		$this->map1to1Item(Ids::COOKED_RABBIT, Items::COOKED_RABBIT());
		$this->map1to1Item(Ids::COOKED_SALMON, Items::COOKED_SALMON());
		$this->map1to1Item(Ids::COOKIE, Items::COOKIE());
		$this->map1to1Item(Ids::COPPER_INGOT, Items::COPPER_INGOT());
		$this->map1to1Item(Ids::CRIMSON_HANGING_SIGN, Items::CRIMSON_HANGING_SIGN());
		$this->map1to1Item(Ids::CRIMSON_SIGN, Items::CRIMSON_SIGN());
		$this->map1to1Item(Ids::DARK_OAK_BOAT, Items::DARK_OAK_BOAT());
		$this->map1to1Item(Ids::DARK_OAK_HANGING_SIGN, Items::DARK_OAK_HANGING_SIGN());
		$this->map1to1Item(Ids::DARK_OAK_SIGN, Items::DARK_OAK_SIGN());
		$this->map1to1Item(Ids::DIAMOND, Items::DIAMOND());
		$this->map1to1Item(Ids::DIAMOND_AXE, Items::DIAMOND_AXE());
		$this->map1to1Item(Ids::DIAMOND_BOOTS, Items::DIAMOND_BOOTS());
		$this->map1to1Item(Ids::DIAMOND_CHESTPLATE, Items::DIAMOND_CHESTPLATE());
		$this->map1to1Item(Ids::DIAMOND_HELMET, Items::DIAMOND_HELMET());
		$this->map1to1Item(Ids::DIAMOND_HOE, Items::DIAMOND_HOE());
		$this->map1to1Item(Ids::DIAMOND_LEGGINGS, Items::DIAMOND_LEGGINGS());
		$this->map1to1Item(Ids::DIAMOND_PICKAXE, Items::DIAMOND_PICKAXE());
		$this->map1to1Item(Ids::DIAMOND_SHOVEL, Items::DIAMOND_SHOVEL());
		$this->map1to1Item(Ids::DIAMOND_SWORD, Items::DIAMOND_SWORD());
		$this->map1to1Item(Ids::DISC_FRAGMENT_5, Items::DISC_FRAGMENT_5());
		$this->map1to1Item(Ids::DRAGON_BREATH, Items::DRAGON_BREATH());
		$this->map1to1Item(Ids::DRIED_KELP, Items::DRIED_KELP());
		$this->map1to1Item(Ids::DUNE_ARMOR_TRIM_SMITHING_TEMPLATE, Items::DUNE_ARMOR_TRIM_SMITHING_TEMPLATE());
		$this->map1to1Item(Ids::ECHO_SHARD, Items::ECHO_SHARD());
		$this->map1to1Item(Ids::EGG, Items::EGG());
		$this->map1to1Item(Ids::EMERALD, Items::EMERALD());
		$this->map1to1Item(Ids::ENCHANTED_BOOK, Items::ENCHANTED_BOOK());
		$this->map1to1Item(Ids::ENCHANTED_GOLDEN_APPLE, Items::ENCHANTED_GOLDEN_APPLE());
		$this->map1to1Item(Ids::ENDER_EYE, Items::ENDER_EYE());
		$this->map1to1Item(Ids::END_CRYSTAL, Items::END_CRYSTAL());
		$this->map1to1Item(Ids::ENDER_PEARL, Items::ENDER_PEARL());
		$this->map1to1Item(Ids::EXPERIENCE_BOTTLE, Items::EXPERIENCE_BOTTLE());
		$this->map1to1Item(Ids::EYE_ARMOR_TRIM_SMITHING_TEMPLATE, Items::EYE_ARMOR_TRIM_SMITHING_TEMPLATE());
		$this->map1to1Item(Ids::FEATHER, Items::FEATHER());
		$this->map1to1Item(Ids::FERMENTED_SPIDER_EYE, Items::FERMENTED_SPIDER_EYE());
		$this->map1to1Item(Ids::FIREWORK_ROCKET, Items::FIREWORK_ROCKET());
		$this->map1to1Item(Ids::FIRE_CHARGE, Items::FIRE_CHARGE());
		$this->map1to1Item(Ids::FISHING_ROD, Items::FISHING_ROD());
		$this->map1to1Item(Ids::FLINT, Items::FLINT());
		$this->map1to1Item(Ids::FLINT_AND_STEEL, Items::FLINT_AND_STEEL());
		$this->map1to1Item(Ids::GHAST_TEAR, Items::GHAST_TEAR());
		$this->map1to1Item(Ids::GLASS_BOTTLE, Items::GLASS_BOTTLE());
		$this->map1to1Item(Ids::GLISTERING_MELON_SLICE, Items::GLISTERING_MELON());
		$this->map1to1Item(Ids::GLOW_BERRIES, Items::GLOW_BERRIES());
		$this->map1to1Item(Ids::GLOW_INK_SAC, Items::GLOW_INK_SAC());
		$this->map1to1Item(Ids::GLOWSTONE_DUST, Items::GLOWSTONE_DUST());
		$this->map1to1Item(Ids::GOLD_INGOT, Items::GOLD_INGOT());
		$this->map1to1Item(Ids::GOLD_NUGGET, Items::GOLD_NUGGET());
		$this->map1to1Item(Ids::GOLDEN_APPLE, Items::GOLDEN_APPLE());
		$this->map1to1Item(Ids::GOLDEN_AXE, Items::GOLDEN_AXE());
		$this->map1to1Item(Ids::GOLDEN_BOOTS, Items::GOLDEN_BOOTS());
		$this->map1to1Item(Ids::GOLDEN_CARROT, Items::GOLDEN_CARROT());
		$this->map1to1Item(Ids::GOLDEN_CHESTPLATE, Items::GOLDEN_CHESTPLATE());
		$this->map1to1Item(Ids::GOLDEN_HELMET, Items::GOLDEN_HELMET());
		$this->map1to1Item(Ids::GOLDEN_HOE, Items::GOLDEN_HOE());
		$this->map1to1Item(Ids::GOLDEN_LEGGINGS, Items::GOLDEN_LEGGINGS());
		$this->map1to1Item(Ids::GOLDEN_PICKAXE, Items::GOLDEN_PICKAXE());
		$this->map1to1Item(Ids::GOLDEN_SHOVEL, Items::GOLDEN_SHOVEL());
		$this->map1to1Item(Ids::GOLDEN_SWORD, Items::GOLDEN_SWORD());
		$this->map1to1Item(Ids::GUNPOWDER, Items::GUNPOWDER());
		$this->map1to1Item(Ids::HEART_OF_THE_SEA, Items::HEART_OF_THE_SEA());
		$this->map1to1Item(Ids::HONEY_BOTTLE, Items::HONEY_BOTTLE());
		$this->map1to1Item(Ids::HONEYCOMB, Items::HONEYCOMB());
		$this->map1to1Item(Ids::HOST_ARMOR_TRIM_SMITHING_TEMPLATE, Items::HOST_ARMOR_TRIM_SMITHING_TEMPLATE());
		$this->map1to1Item(Ids::INK_SAC, Items::INK_SAC());
		$this->map1to1Item(Ids::IRON_AXE, Items::IRON_AXE());
		$this->map1to1Item(Ids::IRON_BOOTS, Items::IRON_BOOTS());
		$this->map1to1Item(Ids::IRON_CHESTPLATE, Items::IRON_CHESTPLATE());
		$this->map1to1Item(Ids::IRON_HELMET, Items::IRON_HELMET());
		$this->map1to1Item(Ids::IRON_HOE, Items::IRON_HOE());
		$this->map1to1Item(Ids::IRON_INGOT, Items::IRON_INGOT());
		$this->map1to1Item(Ids::IRON_LEGGINGS, Items::IRON_LEGGINGS());
		$this->map1to1Item(Ids::IRON_NUGGET, Items::IRON_NUGGET());
		$this->map1to1Item(Ids::IRON_PICKAXE, Items::IRON_PICKAXE());
		$this->map1to1Item(Ids::IRON_SHOVEL, Items::IRON_SHOVEL());
		$this->map1to1Item(Ids::IRON_SWORD, Items::IRON_SWORD());
		$this->map1to1Item(Ids::JUNGLE_BOAT, Items::JUNGLE_BOAT());
		$this->map1to1Item(Ids::JUNGLE_HANGING_SIGN, Items::JUNGLE_HANGING_SIGN());
		$this->map1to1Item(Ids::JUNGLE_SIGN, Items::JUNGLE_SIGN());
		$this->map1to1Item(Ids::LAPIS_LAZULI, Items::LAPIS_LAZULI());
		$this->map1to1Item(Ids::LAVA_BUCKET, Items::LAVA_BUCKET());
		$this->map1to1Item(Ids::LEATHER, Items::LEATHER());
		$this->map1to1Item(Ids::LEATHER_BOOTS, Items::LEATHER_BOOTS());
		$this->map1to1Item(Ids::LEATHER_CHESTPLATE, Items::LEATHER_TUNIC());
		$this->map1to1Item(Ids::LEATHER_HELMET, Items::LEATHER_CAP());
		$this->map1to1Item(Ids::LEATHER_LEGGINGS, Items::LEATHER_PANTS());
		$this->map1to1Item(Ids::MAGMA_CREAM, Items::MAGMA_CREAM());
		$this->map1to1Item(Ids::MANGROVE_BOAT, Items::MANGROVE_BOAT());
		$this->map1to1Item(Ids::MANGROVE_HANGING_SIGN, Items::MANGROVE_HANGING_SIGN());
		$this->map1to1Item(Ids::MANGROVE_SIGN, Items::MANGROVE_SIGN());
		$this->map1to1Item(Ids::MELON_SEEDS, Items::MELON_SEEDS());
		$this->map1to1Item(Ids::MELON_SLICE, Items::MELON());
		$this->map1to1Item(Ids::MILK_BUCKET, Items::MILK_BUCKET());
		$this->map1to1Item(Ids::MINECART, Items::MINECART());
		$this->map1to1Item(Ids::MUSHROOM_STEW, Items::MUSHROOM_STEW());
		$this->map1to1Item(Ids::MUSIC_DISC_11, Items::RECORD_11());
		$this->map1to1Item(Ids::MUSIC_DISC_13, Items::RECORD_13());
		$this->map1to1Item(Ids::MUSIC_DISC_5, Items::RECORD_5());
		$this->map1to1Item(Ids::MUSIC_DISC_BLOCKS, Items::RECORD_BLOCKS());
		$this->map1to1Item(Ids::MUSIC_DISC_CAT, Items::RECORD_CAT());
		$this->map1to1Item(Ids::MUSIC_DISC_CHIRP, Items::RECORD_CHIRP());
		$this->map1to1Item(Ids::MUSIC_DISC_FAR, Items::RECORD_FAR());
		$this->map1to1Item(Ids::MUSIC_DISC_MALL, Items::RECORD_MALL());
		$this->map1to1Item(Ids::MUSIC_DISC_MELLOHI, Items::RECORD_MELLOHI());
		$this->map1to1Item(Ids::MUSIC_DISC_OTHERSIDE, Items::RECORD_OTHERSIDE());
		$this->map1to1Item(Ids::MUSIC_DISC_PIGSTEP, Items::RECORD_PIGSTEP());
		$this->map1to1Item(Ids::MUSIC_DISC_STAL, Items::RECORD_STAL());
		$this->map1to1Item(Ids::MUSIC_DISC_STRAD, Items::RECORD_STRAD());
		$this->map1to1Item(Ids::MUSIC_DISC_WAIT, Items::RECORD_WAIT());
		$this->map1to1Item(Ids::MUSIC_DISC_WARD, Items::RECORD_WARD());
		$this->map1to1Item(Ids::MUTTON, Items::RAW_MUTTON());
		$this->map1to1Item(Ids::NAME_TAG, Items::NAME_TAG());
		$this->map1to1Item(Ids::NAUTILUS_SHELL, Items::NAUTILUS_SHELL());
		$this->map1to1Item(Ids::NETHER_STAR, Items::NETHER_STAR());
		$this->map1to1Item(Ids::NETHERBRICK, Items::NETHER_BRICK());
		$this->map1to1Item(Ids::NETHERITE_AXE, Items::NETHERITE_AXE());
		$this->map1to1Item(Ids::NETHERITE_BOOTS, Items::NETHERITE_BOOTS());
		$this->map1to1Item(Ids::NETHERITE_CHESTPLATE, Items::NETHERITE_CHESTPLATE());
		$this->map1to1Item(Ids::NETHERITE_HELMET, Items::NETHERITE_HELMET());
		$this->map1to1Item(Ids::NETHERITE_HOE, Items::NETHERITE_HOE());
		$this->map1to1Item(Ids::NETHERITE_INGOT, Items::NETHERITE_INGOT());
		$this->map1to1Item(Ids::NETHERITE_LEGGINGS, Items::NETHERITE_LEGGINGS());
		$this->map1to1Item(Ids::NETHERITE_PICKAXE, Items::NETHERITE_PICKAXE());
		$this->map1to1Item(Ids::NETHERITE_SCRAP, Items::NETHERITE_SCRAP());
		$this->map1to1Item(Ids::NETHERITE_SHOVEL, Items::NETHERITE_SHOVEL());
		$this->map1to1Item(Ids::NETHERITE_SWORD, Items::NETHERITE_SWORD());
		$this->map1to1Item(Ids::NETHERITE_UPGRADE_SMITHING_TEMPLATE, Items::NETHERITE_UPGRADE_SMITHING_TEMPLATE());
		$this->map1to1Item(Ids::OAK_BOAT, Items::OAK_BOAT());
		$this->map1to1Item(Ids::OAK_HANGING_SIGN, Items::OAK_HANGING_SIGN());
		$this->map1to1Item(Ids::OAK_SIGN, Items::OAK_SIGN());
		$this->map1to1Item(Ids::PAINTING, Items::PAINTING());
		$this->map1to1Item(Ids::PAPER, Items::PAPER());
		$this->map1to1Item(Ids::PHANTOM_MEMBRANE, Items::PHANTOM_MEMBRANE());
		$this->map1to1Item(Ids::PITCHER_POD, Items::PITCHER_POD());
		$this->map1to1Item(Ids::POISONOUS_POTATO, Items::POISONOUS_POTATO());
		$this->map1to1Item(Ids::POPPED_CHORUS_FRUIT, Items::POPPED_CHORUS_FRUIT());
		$this->map1to1Item(Ids::PORKCHOP, Items::RAW_PORKCHOP());
		$this->map1to1Item(Ids::POTATO, Items::POTATO());
		$this->map1to1Item(Ids::POWDER_SNOW_BUCKET, Items::POWDER_SNOW_BUCKET());
		$this->map1to1Item(Ids::PRISMARINE_CRYSTALS, Items::PRISMARINE_CRYSTALS());
		$this->map1to1Item(Ids::PRISMARINE_SHARD, Items::PRISMARINE_SHARD());
		$this->map1to1Item(Ids::PUFFERFISH, Items::PUFFERFISH());
		$this->map1to1Item(Ids::PUMPKIN_PIE, Items::PUMPKIN_PIE());
		$this->map1to1Item(Ids::PUMPKIN_SEEDS, Items::PUMPKIN_SEEDS());
		$this->map1to1Item(Ids::QUARTZ, Items::NETHER_QUARTZ());
		$this->map1to1Item(Ids::RABBIT, Items::RAW_RABBIT());
		$this->map1to1Item(Ids::RABBIT_FOOT, Items::RABBIT_FOOT());
		$this->map1to1Item(Ids::RABBIT_HIDE, Items::RABBIT_HIDE());
		$this->map1to1Item(Ids::RABBIT_STEW, Items::RABBIT_STEW());
		$this->map1to1Item(Ids::RAISER_ARMOR_TRIM_SMITHING_TEMPLATE, Items::RAISER_ARMOR_TRIM_SMITHING_TEMPLATE());
		$this->map1to1Item(Ids::RAW_COPPER, Items::RAW_COPPER());
		$this->map1to1Item(Ids::RAW_GOLD, Items::RAW_GOLD());
		$this->map1to1Item(Ids::RAW_IRON, Items::RAW_IRON());
		$this->map1to1Item(Ids::RECOVERY_COMPASS, Items::RECOVERY_COMPASS());
		$this->map1to1Item(Ids::REDSTONE, Items::REDSTONE_DUST());
		$this->map1to1Item(Ids::RIB_ARMOR_TRIM_SMITHING_TEMPLATE, Items::RIB_ARMOR_TRIM_SMITHING_TEMPLATE());
		$this->map1to1Item(Ids::ROTTEN_FLESH, Items::ROTTEN_FLESH());
		$this->map1to1Item(Ids::SALMON, Items::RAW_SALMON());
		$this->map1to1Item(Ids::TURTLE_SCUTE, Items::SCUTE());
		$this->map1to1Item(Ids::SENTRY_ARMOR_TRIM_SMITHING_TEMPLATE, Items::SENTRY_ARMOR_TRIM_SMITHING_TEMPLATE());
		$this->map1to1Item(Ids::SHAPER_ARMOR_TRIM_SMITHING_TEMPLATE, Items::SHAPER_ARMOR_TRIM_SMITHING_TEMPLATE());
		$this->map1to1Item(Ids::SHEARS, Items::SHEARS());
		$this->map1to1Item(Ids::SHULKER_SHELL, Items::SHULKER_SHELL());
		$this->map1to1Item(Ids::SILENCE_ARMOR_TRIM_SMITHING_TEMPLATE, Items::SILENCE_ARMOR_TRIM_SMITHING_TEMPLATE());
		$this->map1to1Item(Ids::SLIME_BALL, Items::SLIMEBALL());
		$this->map1to1Item(Ids::SNOUT_ARMOR_TRIM_SMITHING_TEMPLATE, Items::SNOUT_ARMOR_TRIM_SMITHING_TEMPLATE());
		$this->map1to1Item(Ids::SNOWBALL, Items::SNOWBALL());
		$this->map1to1Item(Ids::SPIDER_EYE, Items::SPIDER_EYE());
		$this->map1to1Item(Ids::SPIRE_ARMOR_TRIM_SMITHING_TEMPLATE, Items::SPIRE_ARMOR_TRIM_SMITHING_TEMPLATE());
		$this->map1to1Item(Ids::SPRUCE_BOAT, Items::SPRUCE_BOAT());
		$this->map1to1Item(Ids::SPRUCE_HANGING_SIGN, Items::SPRUCE_HANGING_SIGN());
		$this->map1to1Item(Ids::SPRUCE_SIGN, Items::SPRUCE_SIGN());
		$this->map1to1Item(Ids::SPYGLASS, Items::SPYGLASS());
//		$this->map1to1Item(Ids::SQUID_SPAWN_EGG, Items::SQUID_SPAWN_EGG());
		$this->map1to1Item(Ids::STICK, Items::STICK());
		$this->map1to1Item(Ids::STONE_AXE, Items::STONE_AXE());
		$this->map1to1Item(Ids::STONE_HOE, Items::STONE_HOE());
		$this->map1to1Item(Ids::STONE_PICKAXE, Items::STONE_PICKAXE());
		$this->map1to1Item(Ids::STONE_SHOVEL, Items::STONE_SHOVEL());
		$this->map1to1Item(Ids::STONE_SWORD, Items::STONE_SWORD());
		$this->map1to1Item(Ids::STRING, Items::STRING());
		$this->map1to1Item(Ids::SUGAR, Items::SUGAR());
		$this->map1to1Item(Ids::SWEET_BERRIES, Items::SWEET_BERRIES());
		$this->map1to1Item(Ids::TORCHFLOWER_SEEDS, Items::TORCHFLOWER_SEEDS());
		$this->map1to1Item(Ids::TIDE_ARMOR_TRIM_SMITHING_TEMPLATE, Items::TIDE_ARMOR_TRIM_SMITHING_TEMPLATE());
		$this->map1to1Item(Ids::TOTEM_OF_UNDYING, Items::TOTEM());
		$this->map1to1Item(Ids::TROPICAL_FISH, Items::CLOWNFISH());
		$this->map1to1Item(Ids::TURTLE_HELMET, Items::TURTLE_HELMET());
		$this->map1to1Item(Ids::VEX_ARMOR_TRIM_SMITHING_TEMPLATE, Items::VEX_ARMOR_TRIM_SMITHING_TEMPLATE());
		$this->map1to1Item(Ids::VILLAGER_SPAWN_EGG, Items::VILLAGER_SPAWN_EGG());
		$this->map1to1Item(Ids::WARD_ARMOR_TRIM_SMITHING_TEMPLATE, Items::WARD_ARMOR_TRIM_SMITHING_TEMPLATE());
		$this->map1to1Item(Ids::WARPED_HANGING_SIGN, Items::WARPED_HANGING_SIGN());
		$this->map1to1Item(Ids::WARPED_SIGN, Items::WARPED_SIGN());
		$this->map1to1Item(Ids::WATER_BUCKET, Items::WATER_BUCKET());
		$this->map1to1Item(Ids::WAYFINDER_ARMOR_TRIM_SMITHING_TEMPLATE, Items::WAYFINDER_ARMOR_TRIM_SMITHING_TEMPLATE());
		$this->map1to1Item(Ids::WHEAT, Items::WHEAT());
		$this->map1to1Item(Ids::WHEAT_SEEDS, Items::WHEAT_SEEDS());
		$this->map1to1Item(Ids::WILD_ARMOR_TRIM_SMITHING_TEMPLATE, Items::WILD_ARMOR_TRIM_SMITHING_TEMPLATE());
		$this->map1to1Item(Ids::WIND_CHARGE, Items::WIND_CHARGE());
		$this->map1to1Item(Ids::WOODEN_AXE, Items::WOODEN_AXE());
		$this->map1to1Item(Ids::WOODEN_HOE, Items::WOODEN_HOE());
		$this->map1to1Item(Ids::WOODEN_PICKAXE, Items::WOODEN_PICKAXE());
		$this->map1to1Item(Ids::WOODEN_SHOVEL, Items::WOODEN_SHOVEL());
		$this->map1to1Item(Ids::WOODEN_SWORD, Items::WOODEN_SWORD());
		$this->map1to1Item(Ids::WRITABLE_BOOK, Items::WRITABLE_BOOK());
		$this->map1to1Item(Ids::WRITTEN_BOOK, Items::WRITTEN_BOOK());
		$this->map1to1Item(Ids::ZOMBIE_SPAWN_EGG, Items::ZOMBIE_SPAWN_EGG());

		$this->map1to1Item(Ids::OAK_CHEST_BOAT, Items::OAK_CHEST_BOAT());
		$this->map1to1Item(Ids::SPRUCE_CHEST_BOAT, Items::SPRUCE_CHEST_BOAT());
		$this->map1to1Item(Ids::BIRCH_CHEST_BOAT, Items::BIRCH_CHEST_BOAT());
		$this->map1to1Item(Ids::JUNGLE_CHEST_BOAT, Items::JUNGLE_CHEST_BOAT());
		$this->map1to1Item(Ids::ACACIA_CHEST_BOAT, Items::ACACIA_CHEST_BOAT());
		$this->map1to1Item(Ids::DARK_OAK_CHEST_BOAT, Items::DARK_OAK_CHEST_BOAT());
		$this->map1to1Item(Ids::MANGROVE_CHEST_BOAT, Items::MANGROVE_CHEST_BOAT());
		$this->map1to1Item(Ids::CHERRY_CHEST_BOAT, Items::CHERRY_CHEST_BOAT());
		$this->map1to1Item(Ids::BAMBOO_CHEST_RAFT, Items::BAMBOO_CHEST_RAFT());
		$this->map1to1Item(Ids::CHERRY_BOAT, Items::CHERRY_BOAT());
		$this->map1to1Item(Ids::BAMBOO_RAFT, Items::BAMBOO_RAFT());
		$this->map1to1Item(Ids::ARMADILLO_SCUTE, Items::ARMADILLO_SHUTE());
		$this->map1to1Item(Ids::WARPED_FUNGUS_ON_A_STICK, Items::WARPED_FUNGUS_ON_A_STICK());
		$this->map1to1Item(Ids::TRIDENT, Items::TRIDENT());
		$this->map1to1Item(Ids::TRIAL_KEY, Items::TRIAL_KEY());
		$this->map1to1Item(Ids::SADDLE, Items::SADDLE());
		$this->map1to1Item(Ids::RAPID_FERTILIZER, Items::SUPER_FERTILIZER());
		$this->map1to1Item(Ids::OMINOUS_TRIAL_KEY, Items::OMINOUS_TRIAL_KEY());
		$this->map1to1Item(Ids::OMINOUS_BOTTLE, Items::OMINOUS_BOTTLE());
		$this->map1to1Item(Ids::MUSIC_DISC_RELIC, Items::RECORD_RELIC());
		$this->map1to1Item(Ids::MUSIC_DISC_PRECIPICE, Items::RECORD_PRECIPICE());
		$this->map1to1Item(Ids::MUSIC_DISC_CREATOR, Items::RECORD_CREATOR());
		$this->map1to1Item(Ids::MUSIC_DISC_CREATOR_MUSIC_BOX, Items::RECORD_CREATOR_MUSIC_BOX());
		$this->map1to1Item(Ids::MACE, Items::MACE());
		$this->map1to1Item(Ids::LEAD, Items::LEAD());
		$this->map1to1Item(Ids::EMPTY_MAP, Items::EMPTY_MAP());
		$this->map1to1Item(Ids::ELYTRA, Items::ELYTRA());
		$this->map1to1Item(Ids::DEBUG_STICK, Items::DEBUG_STICK());
		$this->map1to1Item(Ids::TNT_MINECART, Items::TNT_MINECART());
		$this->map1to1Item(Ids::HOPPER_MINECART, Items::HOPPER_MINECART());
		$this->map1to1Item(Ids::CHEST_MINECART, Items::CHEST_MINECART());
		$this->map1to1Item(Ids::FLOW_ARMOR_TRIM_SMITHING_TEMPLATE, Items::FLOW_ARMOR_TRIM_SMITHING_TEMPLATE());
		$this->map1to1Item(Ids::BOLT_ARMOR_TRIM_SMITHING_TEMPLATE, Items::BOLT_ARMOR_TRIM_SMITHING_TEMPLATE());
		$this->map1to1Item(Ids::WOLF_ARMOR, Items::WOLF_ARMOR());
		$this->map1to1Item(Ids::ICE_BOMB, Items::ICE_BOMB());
		$this->map1to1Item(Ids::CROSSBOW, Items::CROSSBOW());
		$this->map1to1Item(Ids::CARROT_ON_A_STICK, Items::CARROT_ON_A_STICK());
		$this->map1to1Item(Ids::BUNDLE, Items::BUNDLE());
		$this->map1to1Item(Ids::BRUSH, Items::BRUSH());
		$this->map1to1Item(Ids::BREEZE_ROD, Items::BREEZE_ROD());
		$this->map1to1Item(Ids::ARMOR_STAND, Items::ARMOR_STAND());
		$this->map1to1Item(Ids::FLOWER_BANNER_PATTERN, Items::FLOWER_CHARGE_BANNER_PATTERN());
		$this->map1to1Item(Ids::CREEPER_BANNER_PATTERN, Items::CREEPER_CHARGE_BANNER_PATTERN());
		$this->map1to1Item(Ids::SKULL_BANNER_PATTERN, Items::SKULL_CHARGE_BANNER_PATTERN());
		$this->map1to1Item(Ids::MOJANG_BANNER_PATTERN, Items::MOJANG_BANNER_PATTERN());
		$this->map1to1Item(Ids::GLOBE_BANNER_PATTERN, Items::GLOBE_BANNER_PATTERN());
		$this->map1to1Item(Ids::PIGLIN_BANNER_PATTERN, Items::PIGLIN_BANNER_PATTERN());
		$this->map1to1Item(Ids::FLOW_BANNER_PATTERN, Items::FLOW_BANNER_PATTERN());
		$this->map1to1Item(Ids::GUSTER_BANNER_PATTERN, Items::GUSTER_BANNER_PATTERN());
		$this->map1to1Item(Ids::FIELD_MASONED_BANNER_PATTERN, Items::FIELD_MASONED_BANNER_PATTERN());
		$this->map1to1Item(Ids::BORDURE_INDENTED_BANNER_PATTERN, Items::BORDURE_INDENTED_BANNER_PATTERN());
		$this->map1to1Item(Ids::ANGLER_POTTERY_SHERD, Items::ANGLER_POTTERY_SHERD());
		$this->map1to1Item(Ids::ARCHER_POTTERY_SHERD, Items::ARCHER_POTTERY_SHERD());
		$this->map1to1Item(Ids::ARMS_UP_POTTERY_SHERD, Items::ARMS_UP_POTTERY_SHERD());
		$this->map1to1Item(Ids::BLADE_POTTERY_SHERD, Items::BLADE_POTTERY_SHERD());
		$this->map1to1Item(Ids::BREWER_POTTERY_SHERD, Items::BREWER_POTTERY_SHERD());
		$this->map1to1Item(Ids::BURN_POTTERY_SHERD, Items::BURN_POTTERY_SHERD());
		$this->map1to1Item(Ids::DANGER_POTTERY_SHERD, Items::DANGER_POTTERY_SHERD());
		$this->map1to1Item(Ids::EXPLORER_POTTERY_SHERD, Items::EXPLORER_POTTERY_SHERD());
		$this->map1to1Item(Ids::FLOW_POTTERY_SHERD, Items::FLOW_POTTERY_SHERD());
		$this->map1to1Item(Ids::FRIEND_POTTERY_SHERD, Items::FRIEND_POTTERY_SHERD());
		$this->map1to1Item(Ids::GUSTER_POTTERY_SHERD, Items::GUSTER_POTTERY_SHERD());
		$this->map1to1Item(Ids::HEART_POTTERY_SHERD, Items::HEART_POTTERY_SHERD());
		$this->map1to1Item(Ids::HEARTBREAK_POTTERY_SHERD, Items::HEARTBREAK_POTTERY_SHERD());
		$this->map1to1Item(Ids::HOWL_POTTERY_SHERD, Items::HOWL_POTTERY_SHERD());
		$this->map1to1Item(Ids::MINER_POTTERY_SHERD, Items::MINER_POTTERY_SHERD());
		$this->map1to1Item(Ids::MOURNER_POTTERY_SHERD, Items::MOURNER_POTTERY_SHERD());
		$this->map1to1Item(Ids::PLENTY_POTTERY_SHERD, Items::PLENTY_POTTERY_SHERD());
		$this->map1to1Item(Ids::PRIZE_POTTERY_SHERD, Items::PRIZE_POTTERY_SHERD());
		$this->map1to1Item(Ids::SCRAPE_POTTERY_SHERD, Items::SCRAPE_POTTERY_SHERD());
		$this->map1to1Item(Ids::SHEAF_POTTERY_SHERD, Items::SHEAF_POTTERY_SHERD());
		$this->map1to1Item(Ids::SHELTER_POTTERY_SHERD, Items::SHELTER_POTTERY_SHERD());
		$this->map1to1Item(Ids::SKULL_POTTERY_SHERD, Items::SKULL_POTTERY_SHERD());
		$this->map1to1Item(Ids::SNORT_POTTERY_SHERD, Items::SNORT_POTTERY_SHERD());
		$this->map1to1Item(Ids::SPARKLER, Items::SPARKLER());
//		$this->map1to1Item(Ids::BLAZE_SPAWN_EGG, Items::BLAZE_SPAWN_EGG());
//		$this->map1to1Item(Ids::BOGGED_SPAWN_EGG, Items::BOGGED_SPAWN_EGG());
//		$this->map1to1Item(Ids::BREEZE_SPAWN_EGG, Items::BREEZE_SPAWN_EGG());
		$this->map1to1Item(Ids::CREEPER_SPAWN_EGG, Items::CREEPER_SPAWN_EGG());
//		$this->map1to1Item(Ids::ELDER_GUARDIAN_SPAWN_EGG, Items::ELDER_GUARDIAN_SPAWN_EGG());
		$this->map1to1Item(Ids::ENDERMITE_SPAWN_EGG, Items::ENDERMITE_SPAWN_EGG());
//		$this->map1to1Item(Ids::EVOKER_SPAWN_EGG, Items::EVOKER_SPAWN_EGG());
//		$this->map1to1Item(Ids::GHAST_SPAWN_EGG, Items::GHAST_SPAWN_EGG());
//		$this->map1to1Item(Ids::GUARDIAN_SPAWN_EGG, Items::GUARDIAN_SPAWN_EGG());
//		$this->map1to1Item(Ids::HOGLIN_SPAWN_EGG, Items::HOGLIN_SPAWN_EGG());
//		$this->map1to1Item(Ids::HUSK_SPAWN_EGG, Items::HUSK_SPAWN_EGG());
//		$this->map1to1Item(Ids::MAGMA_CUBE_SPAWN_EGG, Items::MAGMA_CUBE_SPAWN_EGG());
//		$this->map1to1Item(Ids::PHANTOM_SPAWN_EGG, Items::PHANTOM_SPAWN_EGG());
//		$this->map1to1Item(Ids::PIGLIN_BRUTE_SPAWN_EGG, Items::PIGLIN_BRUTE_SPAWN_EGG());
//		$this->map1to1Item(Ids::PILLAGER_SPAWN_EGG, Items::PILLAGER_SPAWN_EGG());
//		$this->map1to1Item(Ids::RAVAGER_SPAWN_EGG, Items::RAVAGER_SPAWN_EGG());
//		$this->map1to1Item(Ids::SHULKER_SPAWN_EGG, Items::SHULKER_SPAWN_EGG());
//		$this->map1to1Item(Ids::SILVERFISH_SPAWN_EGG, Items::SILVERFISH_SPAWN_EGG());
//		$this->map1to1Item(Ids::SKELETON_SPAWN_EGG, Items::SKELETON_SPAWN_EGG());
		$this->map1to1Item(Ids::SLIME_SPAWN_EGG, Items::SLIME_SPAWN_EGG());
//		$this->map1to1Item(Ids::STRAY_SPAWN_EGG, Items::STRAY_SPAWN_EGG());
//		$this->map1to1Item(Ids::VEX_SPAWN_EGG, Items::VEX_SPAWN_EGG());
//		$this->map1to1Item(Ids::VINDICATOR_SPAWN_EGG, Items::VINDICATOR_SPAWN_EGG());
//		$this->map1to1Item(Ids::WARDEN_SPAWN_EGG, Items::WARDEN_SPAWN_EGG());
//		$this->map1to1Item(Ids::WITCH_SPAWN_EGG, Items::WITCH_SPAWN_EGG());
//		$this->map1to1Item(Ids::WITHER_SKELETON_SPAWN_EGG, Items::WITHER_SKELETON_SPAWN_EGG());
//		$this->map1to1Item(Ids::ZOGLIN_SPAWN_EGG, Items::ZOGLIN_SPAWN_EGG());
//		$this->map1to1Item(Ids::ZOMBIE_VILLAGER_SPAWN_EGG, Items::ZOMBIE_VILLAGER_SPAWN_EGG());
//		$this->map1to1Item(Ids::BEE_SPAWN_EGG, Items::BEE_SPAWN_EGG());
		$this->map1to1Item(Ids::CAVE_SPIDER_SPAWN_EGG, Items::CAVE_SPIDER_SPAWN_EGG());
//		$this->map1to1Item(Ids::DOLPHIN_SPAWN_EGG, Items::DOLPHIN_SPAWN_EGG());
//		$this->map1to1Item(Ids::DROWNED_SPAWN_EGG, Items::DROWNED_SPAWN_EGG());
		$this->map1to1Item(Ids::ENDERMAN_SPAWN_EGG, Items::ENDERMAN_SPAWN_EGG());
//		$this->map1to1Item(Ids::FOX_SPAWN_EGG, Items::FOX_SPAWN_EGG());
//		$this->map1to1Item(Ids::GOAT_SPAWN_EGG, Items::GOAT_SPAWN_EGG());
		$this->map1to1Item(Ids::IRON_GOLEM_SPAWN_EGG, Items::IRON_GOLEM_SPAWN_EGG());
//		$this->map1to1Item(Ids::LLAMA_SPAWN_EGG, Items::LLAMA_SPAWN_EGG());
//		$this->map1to1Item(Ids::PANDA_SPAWN_EGG, Items::PANDA_SPAWN_EGG());
//		$this->map1to1Item(Ids::PIGLIN_SPAWN_EGG, Items::PIGLIN_SPAWN_EGG());
//		$this->map1to1Item(Ids::POLAR_BEAR_SPAWN_EGG, Items::POLAR_BEAR_SPAWN_EGG());
		$this->map1to1Item(Ids::SPIDER_SPAWN_EGG, Items::SPIDER_SPAWN_EGG());
//		$this->map1to1Item(Ids::TRADER_LLAMA_SPAWN_EGG, Items::TRADER_LLAMA_SPAWN_EGG());
//		$this->map1to1Item(Ids::WOLF_SPAWN_EGG, Items::WOLF_SPAWN_EGG());
//		$this->map1to1Item(Ids::ZOMBIE_PIGMAN_SPAWN_EGG, Items::ZOMBIFIED_PIGLIN_SPAWN_EGG());
//		$this->map1to1Item(Ids::ALLAY_SPAWN_EGG, Items::ALLAY_SPAWN_EGG());
//		$this->map1to1Item(Ids::ARMADILLO_SPAWN_EGG, Items::ARMADILLO_SPAWN_EGG());
//		$this->map1to1Item(Ids::AXOLOTL_SPAWN_EGG, Items::AXOLOTL_SPAWN_EGG());
		$this->map1to1Item(Ids::BAT_SPAWN_EGG, Items::BAT_SPAWN_EGG());
//		$this->map1to1Item(Ids::CAMEL_SPAWN_EGG, Items::CAMEL_SPAWN_EGG());
//		$this->map1to1Item(Ids::CAT_SPAWN_EGG, Items::CAT_SPAWN_EGG());
		$this->map1to1Item(Ids::CHICKEN_SPAWN_EGG, Items::CHICKEN_SPAWN_EGG());
//		$this->map1to1Item(Ids::COD_SPAWN_EGG, Items::COD_SPAWN_EGG());
		$this->map1to1Item(Ids::COW_SPAWN_EGG, Items::COW_SPAWN_EGG());
//		$this->map1to1Item(Ids::DONKEY_SPAWN_EGG, Items::DONKEY_SPAWN_EGG());
//		$this->map1to1Item(Ids::FROG_SPAWN_EGG, Items::FROG_SPAWN_EGG());
//		$this->map1to1Item(Ids::GLOW_SQUID_SPAWN_EGG, Items::GLOW_SQUID_SPAWN_EGG());
//		$this->map1to1Item(Ids::HORSE_SPAWN_EGG, Items::HORSE_SPAWN_EGG());
//		$this->map1to1Item(Ids::MOOSHROOM_SPAWN_EGG, Items::MOOSHROOM_SPAWN_EGG());
//		$this->map1to1Item(Ids::MULE_SPAWN_EGG, Items::MULE_SPAWN_EGG());
//		$this->map1to1Item(Ids::OCELOT_SPAWN_EGG, Items::OCELOT_SPAWN_EGG());
//		$this->map1to1Item(Ids::PARROT_SPAWN_EGG, Items::PARROT_SPAWN_EGG());
		$this->map1to1Item(Ids::PIG_SPAWN_EGG, Items::PIG_SPAWN_EGG());
//		$this->map1to1Item(Ids::PUFFERFISH_SPAWN_EGG, Items::PUFFERFISH_SPAWN_EGG());
//		$this->map1to1Item(Ids::RABBIT_SPAWN_EGG, Items::RABBIT_SPAWN_EGG());
//		$this->map1to1Item(Ids::SALMON_SPAWN_EGG, Items::SALMON_SPAWN_EGG());
		$this->map1to1Item(Ids::SHEEP_SPAWN_EGG, Items::SHEEP_SPAWN_EGG());
//		$this->map1to1Item(Ids::SKELETON_HORSE_SPAWN_EGG, Items::SKELETON_HORSE_SPAWN_EGG());
//		$this->map1to1Item(Ids::SNIFFER_SPAWN_EGG, Items::SNIFFER_SPAWN_EGG());
		$this->map1to1Item(Ids::SNOW_GOLEM_SPAWN_EGG, Items::SNOW_GOLEM_SPAWN_EGG());
//		$this->map1to1Item(Ids::STRIDER_SPAWN_EGG, Items::STRIDER_SPAWN_EGG());
//		$this->map1to1Item(Ids::TADPOLE_SPAWN_EGG, Items::TADPOLE_SPAWN_EGG());
//		$this->map1to1Item(Ids::TROPICAL_FISH_SPAWN_EGG, Items::TROPICAL_FISH_SPAWN_EGG());
//		$this->map1to1Item(Ids::TURTLE_SPAWN_EGG, Items::TURTLE_SPAWN_EGG());
//		$this->map1to1Item(Ids::WANDERING_TRADER_SPAWN_EGG, Items::WANDERING_TRADER_SPAWN_EGG());

	}

	/**
	 * Registers mappings for item IDs which map to different PocketMine-MP item types, depending on their meta
	 * values.
	 * This can only be used if the target item type doesn't require any additional properties, since the items are
	 * indexed by their base type ID.
	 */
	private function register1ToNItemMappings() : void{
		$this->map1ToNItem(Ids::ARROW, [
			0 => Items::ARROW(),
			//TODO: tipped arrows
		]);
		$this->map1ToNItem(Ids::COMPOUND, [
			CompoundTypeIds::SALT => Items::CHEMICAL_SALT(),
			CompoundTypeIds::SODIUM_OXIDE => Items::CHEMICAL_SODIUM_OXIDE(),
			CompoundTypeIds::SODIUM_HYDROXIDE => Items::CHEMICAL_SODIUM_HYDROXIDE(),
			CompoundTypeIds::MAGNESIUM_NITRATE => Items::CHEMICAL_MAGNESIUM_NITRATE(),
			CompoundTypeIds::IRON_SULPHIDE => Items::CHEMICAL_IRON_SULPHIDE(),
			CompoundTypeIds::LITHIUM_HYDRIDE => Items::CHEMICAL_LITHIUM_HYDRIDE(),
			CompoundTypeIds::SODIUM_HYDRIDE => Items::CHEMICAL_SODIUM_HYDRIDE(),
			CompoundTypeIds::CALCIUM_BROMIDE => Items::CHEMICAL_CALCIUM_BROMIDE(),
			CompoundTypeIds::MAGNESIUM_OXIDE => Items::CHEMICAL_MAGNESIUM_OXIDE(),
			CompoundTypeIds::SODIUM_ACETATE => Items::CHEMICAL_SODIUM_ACETATE(),
			CompoundTypeIds::LUMINOL => Items::CHEMICAL_LUMINOL(),
			CompoundTypeIds::CHARCOAL => Items::CHEMICAL_CHARCOAL(),
			CompoundTypeIds::SUGAR => Items::CHEMICAL_SUGAR(),
			CompoundTypeIds::ALUMINIUM_OXIDE => Items::CHEMICAL_ALUMINIUM_OXIDE(),
			CompoundTypeIds::BORON_TRIOXIDE => Items::CHEMICAL_BORON_TRIOXIDE(),
			CompoundTypeIds::SOAP => Items::CHEMICAL_SOAP(),
			CompoundTypeIds::POLYETHYLENE => Items::CHEMICAL_POLYETHYLENE(),
			CompoundTypeIds::RUBBISH => Items::CHEMICAL_RUBBISH(),
			CompoundTypeIds::MAGNESIUM_SALTS => Items::CHEMICAL_MAGNESIUM_SALTS(),
			CompoundTypeIds::SULPHATE => Items::CHEMICAL_SULPHATE(),
			CompoundTypeIds::BARIUM_SULPHATE => Items::CHEMICAL_BARIUM_SULPHATE(),
			CompoundTypeIds::POTASSIUM_CHLORIDE => Items::CHEMICAL_POTASSIUM_CHLORIDE(),
			CompoundTypeIds::MERCURIC_CHLORIDE => Items::CHEMICAL_MERCURIC_CHLORIDE(),
			CompoundTypeIds::CERIUM_CHLORIDE => Items::CHEMICAL_CERIUM_CHLORIDE(),
			CompoundTypeIds::TUNGSTEN_CHLORIDE => Items::CHEMICAL_TUNGSTEN_CHLORIDE(),
			CompoundTypeIds::CALCIUM_CHLORIDE => Items::CHEMICAL_CALCIUM_CHLORIDE(),
			CompoundTypeIds::WATER => Items::CHEMICAL_WATER(),
			CompoundTypeIds::GLUE => Items::CHEMICAL_GLUE(),
			CompoundTypeIds::HYPOCHLORITE => Items::CHEMICAL_HYPOCHLORITE(),
			CompoundTypeIds::CRUDE_OIL => Items::CHEMICAL_CRUDE_OIL(),
			CompoundTypeIds::LATEX => Items::CHEMICAL_LATEX(),
			CompoundTypeIds::POTASSIUM_IODIDE => Items::CHEMICAL_POTASSIUM_IODIDE(),
			CompoundTypeIds::SODIUM_FLUORIDE => Items::CHEMICAL_SODIUM_FLUORIDE(),
			CompoundTypeIds::BENZENE => Items::CHEMICAL_BENZENE(),
			CompoundTypeIds::INK => Items::CHEMICAL_INK(),
			CompoundTypeIds::HYDROGEN_PEROXIDE => Items::CHEMICAL_HYDROGEN_PEROXIDE(),
			CompoundTypeIds::AMMONIA => Items::CHEMICAL_AMMONIA(),
			CompoundTypeIds::SODIUM_HYPOCHLORITE => Items::CHEMICAL_SODIUM_HYPOCHLORITE(),
		]);
	}

	/**
	 * Registers mappings for item IDs which map to single blockitems, and have meta values that alter their properties.
	 * TODO: try and make this less ugly; for the most part the logic is symmetrical, it's just difficult to write it
	 * in a unified manner.
	 */
	private function register1to1BlockWithMetaMappings() : void{
		$this->map1to1BlockWithMeta(
			Ids::BED,
			Blocks::BED(),
			function(Bed $block, int $meta) : void{
				$block->setColor(DyeColorIdMap::getInstance()->fromId($meta) ?? throw new ItemTypeDeserializeException("Unknown bed color ID $meta"));
			},
			fn(Bed $block) => DyeColorIdMap::getInstance()->toId($block->getColor())
		);
		$this->map1to1BlockWithMeta(
			Ids::SKULL,
			Blocks::MOB_HEAD(),
			function(MobHead $block, int $meta) : void{
				$block->setMobHeadType(MobHeadTypeIdMap::getInstance()->fromId($meta) ?? throw new ItemTypeDeserializeException("Unknown mob head type ID $meta"));
			},
			fn(MobHead $block) => MobHeadTypeIdMap::getInstance()->toId($block->getMobHeadType())
		);
	}

	/**
	 * Registers mappings for item IDs which map to single items, and have meta values that alter their properties.
	 * TODO: try and make this less ugly; for the most part the logic is symmetrical, it's just difficult to write it
	 * in a unified manner.
	 */
	private function register1to1ItemWithMetaMappings() : void{
		$this->map1to1ItemWithMeta(
			Ids::BANNER,
			Items::BANNER(),
			function(Banner $item, int $meta) : void{
				$item->setColor(DyeColorIdMap::getInstance()->fromInvertedId($meta) ?? throw new ItemTypeDeserializeException("Unknown banner meta $meta"));
			},
			fn(Banner $item) => DyeColorIdMap::getInstance()->toInvertedId($item->getColor())
		);
		$this->map1to1ItemWithMeta(
			Ids::BALLOON,
			Items::BALLOON(),
			function(Balloon $item, int $meta) : void {
				$item->setColor(DyeColorIdMap::getInstance()->fromInvertedId($meta) ?? throw new ItemTypeDeserializeException("Unknown balloon meta $meta"));
			},
			fn(Balloon $item) => DyeColorIdMap::getInstance()->toInvertedId($item->getColor())
		);
		$this->map1to1ItemWithMeta(
			Ids::GLOW_STICK,
			Items::GLOW_STICK(),
			function(GlowStick $item, int $meta) : void {
				$item->setColor(DyeColorIdMap::getInstance()->fromInvertedId($meta) ?? throw new ItemTypeDeserializeException("Unknown balloon meta $meta"));
			},
			fn(GlowStick $item) => DyeColorIdMap::getInstance()->toInvertedId($item->getColor())
		);
		$this->map1to1ItemWithMeta(
			Ids::GOAT_HORN,
			Items::GOAT_HORN(),
			function(GoatHorn $item, int $meta): void {
				$item->setHornType(GoatHornTypeIdMap::getInstance()->fromId($meta) ?? throw new ItemTypeDeserializeException("Unknown goat horn type ID $meta"));
			},
			fn(GoatHorn $item) => GoatHornTypeIdMap::getInstance()->toId($item->getHornType())
		);
		$this->map1to1ItemWithMeta(
			Ids::LINGERING_POTION,
			Items::LINGERING_POTION(),
			function(LingeringPotion $item, int $meta) : void {
				$item->setType(PotionTypeIdMap::getInstance()->fromId($meta) ?? throw new ItemTypeDeserializeException("Unknown potion type ID $meta"));
			},
			fn(LingeringPotion $item) => PotionTypeIdMap::getInstance()->toId($item->getType())
		);
		$this->map1to1ItemWithMeta(
			Ids::FIREWORK_STAR,
			Items::FIREWORK_STAR(),
			function(FireworkStar $item, int $meta) : void {
				// Colors will be defined by CompoundTag deserialization.
			},
			fn(FireworkStar $item) => DyeColorIdMap::getInstance()->toInvertedId($item->getExplosion()->getFlashColor())
		);
		$this->map1to1ItemWithMeta(
			Ids::MEDICINE,
			Items::MEDICINE(),
			function(Medicine $item, int $meta) : void{
				$item->setType(MedicineTypeIdMap::getInstance()->fromId($meta) ?? throw new ItemTypeDeserializeException("Unknown medicine type ID $meta"));
			},
			fn(Medicine $item) => MedicineTypeIdMap::getInstance()->toId($item->getType())
		);
		$this->map1to1ItemWithMeta(
			Ids::POTION,
			Items::POTION(),
			function(Potion $item, int $meta) : void{
				$item->setType(PotionTypeIdMap::getInstance()->fromId($meta) ?? throw new ItemTypeDeserializeException("Unknown potion type ID $meta"));
			},
			fn(Potion $item) => PotionTypeIdMap::getInstance()->toId($item->getType())
		);
		$this->map1to1ItemWithMeta(
			Ids::SPLASH_POTION,
			Items::SPLASH_POTION(),
			function(SplashPotion $item, int $meta) : void{
				$item->setType(PotionTypeIdMap::getInstance()->fromId($meta) ?? throw new ItemTypeDeserializeException("Unknown potion type ID $meta"));
			},
			fn(SplashPotion $item) => PotionTypeIdMap::getInstance()->toId($item->getType())
		);
		$this->map1to1ItemWithMeta(
			Ids::SUSPICIOUS_STEW,
			Items::SUSPICIOUS_STEW(),
			function(SuspiciousStew $item, int $meta) : void{
				$item->setType(SuspiciousStewTypeIdMap::getInstance()->fromId($meta) ?? throw new ItemTypeDeserializeException("Unknown suspicious stew type ID $meta"));
			},
			fn(SuspiciousStew $item) => SuspiciousStewTypeIdMap::getInstance()->toId($item->getType())
		);
	}

	/**
	 * Registers serializers and deserializers for items that don't fit any other pattern.
	 * Ideally we want to get rid of this completely, if possible.
	 *
	 * Most of these are single PocketMine-MP items which map to multiple IDs depending on their properties, which is
	 * complex to implement in a generic way.
	 */
	private function registerMiscItemMappings() : void{
		foreach(DyeColor::cases() as $color){
			$id = DyeColorIdMap::getInstance()->toItemId($color);
			$this->deserializer?->map($id, fn() => Items::DYE()->setColor($color));
		}
		$this->serializer?->map(Items::DYE(), fn(Dye $item) => new Data(DyeColorIdMap::getInstance()->toItemId($item->getColor())));
	}

	/**
	 * Registers serializers and deserializers for PocketMine-MP blockitems that don't fit any other pattern.
	 * Ideally we want to get rid of this completely, if possible.
	 *
	 * Most of these are single PocketMine-MP blocks which map to multiple IDs depending on their properties, which is
	 * complex to implement in a generic way.
	 */
	private function registerMiscBlockMappings() : void{
		$copperDoorStateIdMap = [];
		foreach ([
			[Ids::COPPER_DOOR, CopperOxidation::NONE, false],
			[Ids::EXPOSED_COPPER_DOOR, CopperOxidation::EXPOSED, false],
			[Ids::WEATHERED_COPPER_DOOR, CopperOxidation::WEATHERED, false],
			[Ids::OXIDIZED_COPPER_DOOR, CopperOxidation::OXIDIZED, false],
			[Ids::WAXED_COPPER_DOOR, CopperOxidation::NONE, true],
			[Ids::WAXED_EXPOSED_COPPER_DOOR, CopperOxidation::EXPOSED, true],
			[Ids::WAXED_WEATHERED_COPPER_DOOR, CopperOxidation::WEATHERED, true],
			[Ids::WAXED_OXIDIZED_COPPER_DOOR, CopperOxidation::OXIDIZED, true]
		] as [$id, $oxidation, $waxed]) {
			$copperDoorStateIdMap[$oxidation->value][$waxed ? 1 : 0] = $id;
			$this->deserializer?->mapBlock($id, fn() => Blocks::COPPER_DOOR()->setOxidation($oxidation)->setWaxed($waxed));
		}
		$this->serializer?->mapBlock(Blocks::COPPER_DOOR(), fn(CopperDoor $block) => new Data($copperDoorStateIdMap[$block->getOxidation()->value][$block->isWaxed() ? 1 : 0]));
	}
}
