<?php

namespace pocketmine\entity\pathfinder;

use pocketmine\math\Vector3;
use pocketmine\world\World;
use function abs;

/**
 * @phpstan-import-type BlockPosHash from World
 */
class Node extends Vector3 {

	/** @phpstan-var BlockPosHash */
	private int $hash;

	public int $heapIdx = -1;

	public float $g;
	public float $h;
	public float $f;

	public ?Node $cameFrom = null;

	public bool $closed = false;

	public float $walkedDistance = 0;

	public float $costMalus = 0;

	public BlockPathTypes $type;

	public function __construct(int $x, int $y, int $z) {
		parent::__construct($x, $y, $z);

		$this->hash = self::createHash($x, $y, $z);
		$this->type = BlockPathTypes::BLOCKED();
	}

	public function cloneAndMove(int $x, int $y, int $z) : Node{
		$newNode = clone $this;
		$newNode->x = $x;
		$newNode->y = $y;
		$newNode->z = $z;
		$newNode->hash = self::createHash($x, $y, $z);

		return $newNode;
	}

	/**
	 * @phpstan-return BlockPosHash
	 */
	public static function createHash(int $x, int $y, int $z) : int {
		return World::blockHash($x, $y, $z);
	}

	public function x() : int{
		return (int) $this->x;
	}

	public function y() : int{
		return (int) $this->y;
	}

	public function z() : int{
		return (int) $this->z;
	}

	public function hashCode() : int{
		return $this->hash;
	}

	public function inOpenSet() : bool{
		return $this->heapIdx >= 0;
	}

	public function distanceManhattan(Vector3 $target) : float {
		return abs($target->x - $this->x) + abs($target->y - $this->y) + abs($target->z - $this->z);
	}
}
