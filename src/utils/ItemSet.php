<?php

namespace pocketmine\utils;

use ArrayIterator;
use IteratorAggregate;
use pocketmine\item\Item;

/**
 * @phpstan-implements IteratorAggregate<int, Item>
 */
final class ItemSet implements IteratorAggregate{

	/** @var array<int, Item> */
	private array $elements = [];

	/**
	 * @return $this
	 */
	public function add(Item ...$elements) : self{
		foreach ($elements as $element) {
			$this->elements[$element->getTypeId()] = $element;
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	public function remove(Item ...$elements) : self{
		foreach ($elements as $element) {
			unset($this->elements[$element->getTypeId()]);
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	public function clear() : self{
		$this->elements = [];

		return $this;
	}

	public function contains(Item $element, bool $compareEquals = false) : bool{
		$id = $element->getTypeId();
		return isset($this->elements[$id]) && (!$compareEquals || $this->elements[$id]->equals($element));
	}

	/** @phpstan-return ArrayIterator<int, Item> */
	public function getIterator() : ArrayIterator{
		return new ArrayIterator($this->toArray());
	}

	/**
	 * @phpstan-return array<int, Item>
	 */
	public function toArray() : array{
		return $this->elements;
	}
}
