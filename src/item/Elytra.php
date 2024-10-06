<?php

namespace pocketmine\item;

class Elytra extends Durable{

	public function getMaxDurability() : int{
		return 432;
	}

	public function getMaxStackSize() : int{
		return 1;
	}

}