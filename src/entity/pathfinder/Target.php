<?php

namespace pocketmine\entity\pathfinder;

use const INF;

class Target extends Node{

	protected float $bestHeuristic = INF;

	protected Node $bestNode;

	private bool $reached = false;

	public static function fromObject(Node $node) : Target{
		return new Target($node->x(), $node->y(), $node->z());
	}

	public function updateBest(float $heuristic, Node $node) : void{
		if($heuristic < $this->bestHeuristic){
			$this->bestHeuristic = $heuristic;
			$this->bestNode = $node;
		}
	}

	public function getBestNode() : Node{
		return $this->bestNode;
	}

	public function setReached(bool $reached = true) : void{
		$this->reached = $reached;
	}

	public function reached() : bool{
		return $this->reached;
	}
}
