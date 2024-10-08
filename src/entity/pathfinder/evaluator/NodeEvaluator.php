<?php

namespace pocketmine\entity\pathfinder\evaluator;

use pocketmine\entity\Mob;
use pocketmine\entity\pathfinder\BlockPathTypes;
use pocketmine\entity\pathfinder\Node;
use pocketmine\entity\pathfinder\Target;
use pocketmine\math\Vector3;
use pocketmine\world\World;

use function floor;

/**
 * @phpstan-import-type BlockPosHash from World
 */
abstract class NodeEvaluator {

	protected World $world;

	protected Mob $mob;

	/** @var array<BlockPosHash, Node> */
	protected array $nodes = [];

	protected int $entityWidth;
	protected int $entityHeight;
	protected int $entityDepth;

	protected bool $canPassDoors = false;
	protected bool $canOpenDoors = false;
	protected bool $canFloat = false;
	protected bool $canWalkOverFences = false;

	public function prepare(World $world, Mob $mob) : void{
		$this->world = $world;
		$this->mob = $mob;

		$this->nodes = [];

		$this->entityWidth = (int) floor($mob->getSize()->getWidth() + 1);
		$this->entityHeight = (int) floor($mob->getSize()->getHeight() + 1);
		$this->entityDepth = (int) floor($mob->getSize()->getWidth() + 1);
	}

	public function done() : void{
		unset($this->world);
		unset($this->mob);
	}

	public function getNode(Vector3 $pos) : Node{
		return $this->getNodeAt((int) floor($pos->x), (int) floor($pos->y), (int) floor($pos->z));
	}

	public function getNodeAt(int $x, int $y, int $z) : Node{
		$hash = Node::createHash($x, $y, $z);
		if (!isset($this->nodes[$hash])) {
			$this->nodes[$hash] = new Node($x, $y, $z);
		}
		return $this->nodes[$hash];
	}

	public abstract function getStart() : Node;

	public abstract function getGoal(float $x, float $y, float $z) : Target;

	protected function getTargetFromNode(Node $node) : Target {
		return Target::fromObject($node);
	}

	/**
	 * @return Node[]
	 */
	public abstract function getNeighbors(Node $node) : array;

	public abstract function getBlockPathTypeWithMob(World $world, int $x, int $y, int $z, Mob $mob) : BlockPathTypes;

	public abstract function getBlockPathType(World $world, int $x, int $y, int $z) : BlockPathTypes;

	public function setCanPassDoors(bool $canPassDoors = true) : void {
		$this->canPassDoors = $canPassDoors;
	}

	public function setCanOpenDoors(bool $canOpenDoors = true) : void {
		$this->canOpenDoors = $canOpenDoors;
	}

	public function setCanFloat(bool $canFloat = true) : void {
		$this->canFloat = $canFloat;
	}

	public function setCanWalkOverFences(bool $canWalkOverFences = true) : void {
		$this->canWalkOverFences = $canWalkOverFences;
	}

	public function canPassDoors() : bool {
		return $this->canPassDoors;
	}

	public function canOpenDoors() : bool {
		return $this->canOpenDoors;
	}

	public function canFloat() : bool {
		return $this->canFloat;
	}

	public function canWalkOverFences() : bool{
		return $this->canWalkOverFences;
	}
}
