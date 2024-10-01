<?php

namespace pocketmine\world\particle;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\SpawnParticleEffectPacket;
use pocketmine\network\mcpe\protocol\types\DimensionIds;

class CropGrowthEmitterParticle implements Particle{

	public const CROP_GROWTH_EMITTER_PARTICLE_ID = "minecraft:crop_growth_emitter";

	public function encode(Vector3 $pos) : array{
		return [SpawnParticleEffectPacket::create(
			DimensionIds::OVERWORLD,
			-1,
			$pos,
			self::CROP_GROWTH_EMITTER_PARTICLE_ID,
			null
		)];
	}

}