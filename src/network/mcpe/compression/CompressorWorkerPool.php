<?php

namespace pocketmine\network\mcpe\compression;

use pocketmine\snooze\SleeperHandler;
use function array_merge;
use function array_slice;
use function ceil;
use function count;

final class CompressorWorkerPool{

	/**
	 * @var CompressorWorker[]
	 * @phpstan-var array<int, CompressorWorker>
	 */
	private array $workers = [];

	private int $nextWorker = 0;

	public function __construct(
		private readonly int $maxSize,
		private readonly Compressor $compressor,
		private readonly SleeperHandler $sleeperHandler,
	){}

	public function getCompressor() : Compressor{ return $this->compressor; }

	public function submit(string $buffer) : CompressBatchPromise{
		$worker = $this->workers[$this->nextWorker] ?? null;
		if($worker === null){
			$worker = new CompressorWorker($this->compressor, $this->sleeperHandler);
			$this->workers[$this->nextWorker] = $worker;
		}
		$this->nextWorker = ($this->nextWorker + 1) % $this->maxSize;
		return $worker->submit($buffer);
	}

	/**
	 * @param string[] $buffers
	 * @return CompressBatchPromise[]
	 */
	public function submitBulk(array $buffers) : array{
		$splitSize = (int) ceil(count($buffers) / $this->maxSize);

		$results = [];
		$offset = 0;
		for($i = 0; $i < $this->maxSize; $i++){
			$worker = $this->workers[$i] ??= new CompressorWorker($this->compressor, $this->sleeperHandler);

			$results[] = $worker->submitBulk(array_slice($buffers, $offset, $splitSize, true));
			$offset += $splitSize;
			if($offset >= count($buffers)){
				break;
			}
		}
		return array_merge(...$results);
	}

	public function shutdown() : void{
		foreach($this->workers as $worker){
			$worker->shutdown();
		}
		$this->workers = [];
	}

	public function __destruct(){
		$this->shutdown();
	}
}