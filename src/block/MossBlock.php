<?php

namespace pocketmine\block;

use pocketmine\item\Fertilizer;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\Random;
use pocketmine\world\particle\HappyVillagerParticle;
use pocketmine\world\Position;

class MossBlock extends Opaque{

	public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []) : bool{
		if($item instanceof Fertilizer){
			$this->convertToMoss($this->getPosition());
			$this->populateRegion($this->getPosition());
			$this->getPosition()->getWorld()->addParticle($this->getPosition()->add(0.5, 1.5, 0.5), new HappyVillagerParticle()); // same as crop growth particle
			$item->pop();
			return true;
		}
		return parent::onInteract($item, $face, $clickVector, $player, $returnedItems);
	}

	private function canConvertToMoss(Block $block) : bool{
		// todo rooted dirt should be here but its not in blocktypeids?
		return match ($block->getTypeId()) {
			BlockTypeIds::GRASS,
			BlockTypeIds::DIRT,
			BlockTypeIds::STONE,
			BlockTypeIds::MYCELIUM,
			BlockTypeIds::TUFF,
			BlockTypeIds::ANDESITE,
			BlockTypeIds::DIORITE,
			BlockTypeIds::GRANITE,
			BlockTypeIds::PODZOL,
			BlockTypeIds::POLISHED_ANDESITE,
			BlockTypeIds::POLISHED_DIORITE,
			BlockTypeIds::POLISHED_GRANITE,
			BlockTypeIds::DEEPSLATE => true,
			default => false,
		};
	}

	public function canBePopulated(Position $pos) : bool{
		return $pos->getWorld()->getBlock($pos->add(0, -1, 0))->getTypeId() !== BlockTypeIds::MOSS_CARPET
			&& $pos->getWorld()->getBlock($pos)->getTypeId() === BlockTypeIds::AIR;
	}


	private function canBePopulated2BlockAir(Position $position) : bool{
		return (
			$position->getWorld()->getBlock($position->add(0, -1, 0))->getTypeId() !== BlockTypeIds::MOSS_CARPET &&
			$position->getWorld()->getBlock($position)->getTypeId() === BlockTypeIds::AIR &&
			$position->getWorld()->getBlock($position->add(0, 1, 0))->getTypeId() === BlockTypeIds::AIR
		);
	}

	private function convertToMoss(Position $position) : void{
		$random = new Random();
		for($x = $position->x - 3; $x <= $position->x + 3; $x++){
			for($z = $position->z - 3; $z <= $position->z + 3; $z++){
				for($y = $position->y + 5; $y >= $position->y - 5; $y--){
					if($this->canConvertToMoss($position->getWorld()->getBlock(new Position($x, $y, $z, $position->getWorld()))) && ($random->nextFloat() < 0.6 || abs($x - $position->x) < 3 && abs($z - $position->z) < 3)){
						$position->getWorld()->setBlock(new Position($x, $y, $z, $position->getWorld()), VanillaBlocks::MOSS_BLOCK());
						break;
					}
				}
			}
		}
	}

	private function populateRegion(Position $position) : void{
		$random = new Random();
		for($x = $position->x - 3; $x <= $position->x + 3; $x++){
			for($z = $position->z - 3; $z <= $position->z + 3; $z++){
				for($y = $position->y + 5; $y >= $position->y - 5; $y--){
					if($this->canBePopulated(new Position($x, $y, $z, $position->getWorld()))){
						if(!$this->canGrowPlant(new Position($x, $y, $z, $position->getWorld()))){
							continue;
						}
						$randomFloat = $random->nextFloat();
						if($randomFloat >= 0 && $randomFloat < 0.3125){
							$position->getWorld()->setBlockAt($x, $y + 1, $z, VanillaBlocks::TALL_GRASS());
						}
						if($randomFloat >= 0.3125 && $randomFloat < 0.46875){
							$position->getWorld()->setBlock(new Position($x, $y, $z, $position->getWorld()), VanillaBlocks::MOSS_CARPET()); // todo double tall grass
						}
						if($randomFloat >= 0.46875 && $randomFloat < 0.53125){
							if($this->canBePopulated2BlockAir(new Position($x, $y, $z, $position->getWorld()))){
								$position->getWorld()->setBlock(new Position($x, $y, $z, $position->getWorld()), VanillaBlocks::FERN()); // todo tall fern
							}else{
								$position->getWorld()->setBlock(new Position($x, $y, $z, $position->getWorld()), VanillaBlocks::TALL_GRASS()); // todo double tall grass
							}
						}
						if ($randomFloat >= 0.53125 && $randomFloat < 0.575) {
							$position->getWorld()->setBlock(new Position($x, $y, $z, $position->getWorld()), VanillaBlocks::AZALEA());
						}
						if ($randomFloat >= 0.575 && $randomFloat < 0.6) {
							$position->getWorld()->setBlock(new Position($x, $y, $z, $position->getWorld()), VanillaBlocks::FLOWERING_AZALEA());
						}
						if($randomFloat >= 0.6 && $randomFloat < 1){
							$position->getWorld()->setBlock(new Position($x, $y, $z, $position->getWorld()), VanillaBlocks::AIR()); // todo can this be removed?
						}
						break;
					}
				}
			}
		}
	}

	private function canGrowPlant(Position $position) : bool{
		// todo rooted dirt should be here
		return match ($position->getWorld()->getBlock($position->add(0, -1, 0))->getTypeId()) {
			BlockTypeIds::GRASS,
			BlockTypeIds::DIRT,
			BlockTypeIds::PODZOL,
			BlockTypeIds::FARMLAND,
			BlockTypeIds::MYCELIUM,
			BlockTypeIds::MOSS_BLOCK => true,
			default => false,
		};
	}

}