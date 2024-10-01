<?php

namespace pocketmine\inventory\transaction\action;

use pocketmine\block\Beacon;
use pocketmine\block\inventory\BeaconInventory;
use pocketmine\entity\effect\Effect;
use pocketmine\event\block\BeaconActivateEvent;
use pocketmine\inventory\transaction\TransactionValidationException;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class BeaconPaymentAction extends InventoryAction{

	public function __construct(
		protected BeaconInventory $inventory,
		protected Effect $primaryEffect,
		protected ?Effect $secondaryEffect
	){
		parent::__construct(VanillaItems::AIR(), VanillaItems::AIR());
	}

	public function getInventory() : BeaconInventory{
		return $this->inventory;
	}

	public function validate(Player $source) : void{
		$input = $this->inventory->getInput();
		if(!isset(Beacon::ALLOWED_ITEM_IDS[$input->getTypeId()]) || $input->getCount() < 1){
			throw new TransactionValidationException("Invalid input item");
		}

		$position = $this->inventory->getHolder();
		$block = $position->getWorld()->getBlock($position);
		if(!$block instanceof Beacon){
			throw new TransactionValidationException("Target block is not a beacon");
		}

		$allowedEffects = $block->getAllowedEffects($block->getBeaconLevel());
		if(!in_array($this->primaryEffect, $allowedEffects, true)){ // todo Avoid using in_array when possible
			throw new TransactionValidationException("Primary effect provided is not allowed");
		}
		if($this->secondaryEffect !== null && !in_array($this->secondaryEffect, $allowedEffects, true)){ // todo This validation is incomplete. Secondary effect can only be Regeneration or the same as primary effect. Current code allows that a hacked-cleint to send a primary effect as secondary.
			throw new TransactionValidationException("Secondary effect provided is not allowed");
		}
	}

	public function onPreExecute(Player $source) : bool{
		$position = $this->inventory->getHolder();
		$block = $position->getWorld()->getBlock($position);

		assert($block instanceof Beacon);

		$beaconLevel = $block->getBeaconLevel();

		$ev = new BeaconActivateEvent($block, $this->primaryEffect, $beaconLevel >= Beacon::MAX_LEVEL ? $this->secondaryEffect : null);

		if($beaconLevel < 1 || !$block->viewSky()){
			$ev->cancel();
		}

		$ev->call();
		if($ev->isCancelled()){
			return false;
		}

		$this->primaryEffect = $ev->getPrimaryEffect();
		$this->secondaryEffect = $ev->getSecondaryEffect();

		return true;
	}

	public function execute(Player $source) : void{
		$position = $this->inventory->getHolder();
		$world = $position->getWorld();
		$block = $world->getBlock($position);

		assert($block instanceof Beacon);

		$block->setPrimaryEffect($this->primaryEffect);
		$block->setSecondaryEffect($this->secondaryEffect);

		$input = $this->inventory->getInput();
		if($input->getCount() > 0){
			$input->pop();
			$this->inventory->setInput($input);
		}

		$world->setBlock($position, $block);
		$world->scheduleDelayedBlockUpdate($position, 20);
	}
}