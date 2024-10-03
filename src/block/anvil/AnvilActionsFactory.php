<?php

namespace pocketmine\block\anvil;

use pocketmine\item\Item;
use pocketmine\utils\SingletonTrait;
use function is_subclass_of;

final class AnvilActionsFactory{
	use SingletonTrait;

	/** @var array<class-string<AnvilAction>, true> */
	private array $actions = [];

	private function __construct(){
		$this->register(RenameItemAction::class);
		$this->register(CombineEnchantmentsAction::class);
		$this->register(RepairWithSacrificeAction::class);
		$this->register(RepairWithMaterialAction::class);
	}

	/**
	 * @param class-string<AnvilAction> $class
	 */
	public function register(string $class) : void{
		if(!is_subclass_of($class, AnvilAction::class, true)){
			throw new \InvalidArgumentException("Class $class is not an AnvilAction");
		}
		if(isset($this->actions[$class])){
			throw new \InvalidArgumentException("Class $class is already registered");
		}
		$this->actions[$class] = true;
	}

	/**
	 * Return all available actions for the given items.
	 *
	 * @return AnvilAction[]
	 */
	public function getActions(Item $base, Item $material, ?string $customName) : array{
		$actions = [];
		foreach($this->actions as $class => $_){
			$action = new $class($base, $material, $customName);
			if($action->canBeApplied()){
				$actions[] = $action;
			}
		}
		return $actions;
	}
}