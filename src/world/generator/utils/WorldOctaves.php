<?php

namespace pocketmine\world\generator\utils;

use pocketmine\world\generator\noise\bukkit\OctaveGenerator;

/**
 * @template T of OctaveGenerator
 * @template U of OctaveGenerator
 * @template V of OctaveGenerator
 * @template W of OctaveGenerator
 */
class WorldOctaves{

	/**
	 * @param T $height
	 * @param U $roughness
	 * @param U $roughness_2
	 * @param V $detail
	 * @param W $surface
	 */
	public function __construct(
		public OctaveGenerator $height,
		public OctaveGenerator $roughness,
		public OctaveGenerator $roughness_2,
		public OctaveGenerator $detail,
		public OctaveGenerator $surface
	){}
}