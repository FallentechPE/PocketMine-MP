<?php

namespace pocketmine\scheduler;

use pocketmine\timings\TimingsHandler;

final class TimingsControlTask extends AsyncTask{

	public const ENABLE = 1;
	public const DISABLE = 2;
	public const RESET = 3;

	public function __construct(
		private int $operation
	){}

	public function onRun() : void{
		if($this->operation === self::ENABLE){
			TimingsHandler::setEnabled(true);
			\GlobalLogger::get()->debug("Enabled timings");
		}elseif($this->operation === self::DISABLE){
			TimingsHandler::setEnabled(false);
			\GlobalLogger::get()->debug("Disabled timings");
		}elseif($this->operation === self::RESET){
			TimingsHandler::reload();
			\GlobalLogger::get()->debug("Reset timings");
		}else{
			throw new \InvalidArgumentException("Invalid operation $this->operation");
		}
	}
}