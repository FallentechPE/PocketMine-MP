<?php

namespace pocketmine\world\particle;

use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\LevelEventGenericPacket;
use pocketmine\network\mcpe\protocol\types\LevelEvent;

class TeleportTrailParticle implements Particle{

	private const TAG_PARTICLE_COUNT = "Count";

	private const TAG_DIR_SCALE = "DirScale";

	private const TAG_FROM_X = "Startx";
	private const TAG_FROM_Y = "Starty";
	private const TAG_FROM_Z = "Startz";

	private const TAG_TO_X = "Endx";
	private const TAG_TO_Y = "Endy";
	private const TAG_TO_Z = "Endz";

	private const TAG_HORIZONTAL_VARIATION = "Variationx";
	private const TAG_VERTICAL_VARIATION = "Variationy";

	public const DEFAULT_PARTICLE_COUNT = 128;
	public const DEFAULT_DIR_SCALE = 0.2;

	public function __construct(
		private Vector3 $to,
		private float $horizontalVariation,
		private float $verticalVariation,
		private int $count = self::DEFAULT_PARTICLE_COUNT,
		private float $dirScale = self::DEFAULT_DIR_SCALE
	){}

	public function encode(Vector3 $pos) : array{
		return [LevelEventGenericPacket::create(LevelEvent::PARTICLE_TELEPORT_TRAIL, CompoundTag::create()
			->setFloat(self::TAG_FROM_X, $pos->x)
			->setFloat(self::TAG_FROM_Y, $pos->y)
			->setFloat(self::TAG_FROM_Z, $pos->z)
			->setFloat(self::TAG_TO_X, $this->to->x)
			->setFloat(self::TAG_TO_Y, $this->to->y)
			->setFloat(self::TAG_TO_Z, $this->to->z)
			->setFloat(self::TAG_HORIZONTAL_VARIATION, $this->horizontalVariation)
			->setFloat(self::TAG_VERTICAL_VARIATION, $this->verticalVariation)
			->setFloat(self::TAG_DIR_SCALE, $this->dirScale)
			->setInt(self::TAG_PARTICLE_COUNT, $this->count)
		)];
	}
}
