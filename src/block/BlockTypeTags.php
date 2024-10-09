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

namespace pocketmine\block;

final class BlockTypeTags{
	private const PREFIX = "pocketmine:";

	public const DIRT = self::PREFIX . "dirt"; // dirt, farmland, grass, mycelium, podzol
	public const MUD = self::PREFIX . "mud"; // mud, muddy mangrove roots
	public const SAND = self::PREFIX . "sand"; // sand, red sand
	public const POTTABLE_PLANTS = self::PREFIX . "pottable"; // brown mushroom, cactus, deadbush, flowers, red mushroom, fern, saplings, azaleas, wither rose, nether roots
	public const FIRE = self::PREFIX . "fire"; // fire, soul fire
}
