<?php

namespace pocketmine\utils;

/**
 * @phpstan-template TKey
 * @phpstan-template TValue
 */
class Pair {

	private mixed $key;

	private mixed $value;

	/** @phpstan-param TKey $key */
	/** @phpstan-param TValue $value */
	public function __construct(mixed $key, mixed $value) {
		$this->key = $key;
		$this->value = $value;
	}

	/** @phpstan-return TKey */
	public function getKey() : mixed {
		return $this->key;
	}

	/** @phpstan-return TValue */
	public function getValue() : mixed {
		return $this->value;
	}

	/** @phpstan-param TValue $value */
	public function setValue(mixed $value) : void {
		$this->value = $value;
	}
}
