<?php

namespace pocketmine\event\player;


use pocketmine\event\Event;
use pocketmine\network\mcpe\handler\PacketHandler;
use pocketmine\network\mcpe\NetworkSession;

class PlayerPacketHandlerChangeEvent extends Event{
	public function __construct(
		private NetworkSession $session,
		private ?PacketHandler $handler
	){ }

	public function getNetworkSession() : NetworkSession{
		return $this->session;
	}

	public function getHandler() : ?PacketHandler{
		return $this->handler;
	}

	public function setHandler(?PacketHandler $handler) : void{
		$this->handler = $handler;
	}
}