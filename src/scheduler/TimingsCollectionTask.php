<?php

namespace pocketmine\scheduler;

use pmmp\thread\Thread as NativeThread;
use pocketmine\promise\PromiseResolver;
use pocketmine\timings\TimingsHandler;

/**
 * @phpstan-type Resolver PromiseResolver<list<string>>
 */
final class TimingsCollectionTask extends AsyncTask{
	private const TLS_KEY_RESOLVER = "resolver";

	/**
	 * @phpstan-param PromiseResolver<list<string>> $promiseResolver
	 */
	public function __construct(PromiseResolver $promiseResolver){
		$this->storeLocal(self::TLS_KEY_RESOLVER, $promiseResolver);
	}

	public function onRun() : void{
		$this->setResult(TimingsHandler::printCurrentThreadRecords(NativeThread::getCurrentThreadId()));
	}

	public function onCompletion() : void{
		/**
		 * @var string[] $result
		 * @phpstan-var list<string> $result
		 */
		$result = $this->getResult();
		/**
		 * @var PromiseResolver $promiseResolver
		 * @phpstan-var PromiseResolver<list<string>> $promiseResolver
		 */
		$promiseResolver = $this->fetchLocal(self::TLS_KEY_RESOLVER);

		$promiseResolver->resolve($result);
	}
}