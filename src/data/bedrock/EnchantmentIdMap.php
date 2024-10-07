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

namespace pocketmine\data\bedrock;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\utils\SingletonTrait;

/**
 * Handles translation of internal enchantment types to and from Minecraft: Bedrock IDs.
 */
final class EnchantmentIdMap{
	use SingletonTrait;

	/** @phpstan-use IntSaveIdMapTrait<Enchantment> */
	use IntSaveIdMapTrait;

	private function __construct(){
		$this->register(EnchantmentIds::PROTECTION, VanillaEnchantments::PROTECTION());
		$this->register(EnchantmentIds::FIRE_PROTECTION, VanillaEnchantments::FIRE_PROTECTION());
		$this->register(EnchantmentIds::FEATHER_FALLING, VanillaEnchantments::FEATHER_FALLING());
		$this->register(EnchantmentIds::BLAST_PROTECTION, VanillaEnchantments::BLAST_PROTECTION());
		$this->register(EnchantmentIds::PROJECTILE_PROTECTION, VanillaEnchantments::PROJECTILE_PROTECTION());
		$this->register(EnchantmentIds::THORNS, VanillaEnchantments::THORNS());
		$this->register(EnchantmentIds::RESPIRATION, VanillaEnchantments::RESPIRATION());
		$this->register(EnchantmentIds::AQUA_AFFINITY, VanillaEnchantments::AQUA_AFFINITY());

		$this->register(EnchantmentIds::SHARPNESS, VanillaEnchantments::SHARPNESS());
		$this->register(EnchantmentIds::SMITE, VanillaEnchantments::SMITE());
		$this->register(EnchantmentIds::BANE_OF_ARTHROPODS, VanillaEnchantments::BANE_OF_ARTHROPODS());

		$this->register(EnchantmentIds::KNOCKBACK, VanillaEnchantments::KNOCKBACK());
		$this->register(EnchantmentIds::FIRE_ASPECT, VanillaEnchantments::FIRE_ASPECT());

		$this->register(EnchantmentIds::EFFICIENCY, VanillaEnchantments::EFFICIENCY());
		$this->register(EnchantmentIds::FORTUNE, VanillaEnchantments::FORTUNE());
		$this->register(EnchantmentIds::SILK_TOUCH, VanillaEnchantments::SILK_TOUCH());
		$this->register(EnchantmentIds::UNBREAKING, VanillaEnchantments::UNBREAKING());

		$this->register(EnchantmentIds::POWER, VanillaEnchantments::POWER());
		$this->register(EnchantmentIds::PUNCH, VanillaEnchantments::PUNCH());
		$this->register(EnchantmentIds::FLAME, VanillaEnchantments::FLAME());
		$this->register(EnchantmentIds::INFINITY, VanillaEnchantments::INFINITY());

		$this->register(EnchantmentIds::MENDING, VanillaEnchantments::MENDING());

		$this->register(EnchantmentIds::VANISHING, VanillaEnchantments::VANISHING());

		$this->register(EnchantmentIds::SWIFT_SNEAK, VanillaEnchantments::SWIFT_SNEAK());

		$this->register(EnchantmentIds::FROST_WALKER, VanillaEnchantments::FROST_WALKER());
		$this->register(EnchantmentIds::BREACH, VanillaEnchantments::BREACH());
		$this->register(EnchantmentIds::CHANNELING, VanillaEnchantments::CHANNELING());
		$this->register(EnchantmentIds::BINDING, VanillaEnchantments::CURSE_OF_BINDING());
		$this->register(EnchantmentIds::DENSITY, VanillaEnchantments::DENSITY());
		$this->register(EnchantmentIds::IMPALING, VanillaEnchantments::IMPALING());
		$this->register(EnchantmentIds::LOOTING, VanillaEnchantments::LOOTING());
		$this->register(EnchantmentIds::LOYALTY, VanillaEnchantments::LOYALTY());
		$this->register(EnchantmentIds::LURE, VanillaEnchantments::LURE());
		$this->register(EnchantmentIds::LUCK_OF_THE_SEA, VanillaEnchantments::LUCK_OF_THE_SEA());
		$this->register(EnchantmentIds::MULTISHOT, VanillaEnchantments::MULTISHOT());
		$this->register(EnchantmentIds::PIERCING, VanillaEnchantments::PIERCING());
		$this->register(EnchantmentIds::QUICK_CHARGE, VanillaEnchantments::QUICK_CHARGE());
		$this->register(EnchantmentIds::RIPTIDE, VanillaEnchantments::RIPTIDE());
		$this->register(EnchantmentIds::WIND_BURST, VanillaEnchantments::WIND_BURST());
		$this->register(EnchantmentIds::SOUL_SPEED, VanillaEnchantments::SOUL_SPEED());
		$this->register(EnchantmentIds::DEPTH_STRIDER, VanillaEnchantments::DEPTH_STRIDER());
	}
}
