<?php

namespace pocketmine\command;

use pocketmine\lang\Translatable;
use pocketmine\utils\Utils;

/**
 * @phpstan-type Execute \Closure(CommandSender $sender, Command $command, string $commandLabel, list<string> $args) : mixed
 */
final class ClosureCommand extends Command{
	/** @phpstan-var Execute */
	private \Closure $execute;

	/**
	 * @phpstan-param Execute $execute
	 */
	public function __construct(
		string $name,
		\Closure $execute,
		Translatable|string $description = "",
		Translatable|string|null $usageMessage = null,
		array $aliases = []
	){
		Utils::validateCallableSignature(
			fn(CommandSender $sender, Command $command, string $commandLabel, array $args) : mixed => 1,
			$execute,
		);
		$this->execute = $execute;
		parent::__construct($name, $description, $usageMessage, $aliases);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		return ($this->execute)($sender, $this, $commandLabel, $args);
	}
}