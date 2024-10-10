<?php

namespace pocketmine\entity\object\armorstand\behavior;

use pocketmine\block\VanillaBlocks;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\Armor;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;

final class ArmorStandBehaviorRegistry{

	/** @var ArmorStandBehavior[] */
	private array $behaviours = [];

	private ArmorStandBehavior $fallback;

	public function __construct(){
		$this->registerFallback(new HeldItemArmorStandBehavior());

		foreach(VanillaItems::getAll() as $item){
			if($item instanceof Armor){
				$this->register($item, new ArmorPieceArmorStandBehavior($item->getArmorSlot()));
			}
		}

		$this->register(VanillaBlocks::MOB_HEAD()->asItem(), new ArmorPieceArmorStandBehavior(ArmorInventory::SLOT_HEAD));
		$this->register(VanillaBlocks::CARVED_PUMPKIN()->asItem(), new ArmorPieceArmorStandBehavior(ArmorInventory::SLOT_HEAD));
		$this->register(VanillaItems::AIR(), new NullItemArmorStandBehavior());
	}

	public function register(Item $item, ArmorStandBehavior $behaviour) : void{
		$this->behaviours[$item->getTypeId()] = $behaviour;
	}

	public function registerFallback(ArmorStandBehavior $behaviour) : void{
		$this->fallback = $behaviour;
	}

	public function get(Item $item) : ArmorStandBehavior{
		return $this->behaviours[$item->getTypeId()] ?? $this->fallback;
	}
}