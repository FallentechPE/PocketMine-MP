<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
 */

declare(strict_types=1);

/**
 * Various Utilities used around the code
 */

namespace pocketmine\utils;

use DaveRandom\CallbackValidator\CallbackType;
use Generator;
use InvalidArgumentException;
use pocketmine\block\Block;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\Door;
use pocketmine\block\Slab;
use pocketmine\block\Water;
use pocketmine\entity\ai\targeting\TargetingConditions;
use pocketmine\entity\Living;
use pocketmine\entity\Location;
use pocketmine\entity\pathfinder\PathComputationType;
use pocketmine\errorhandler\ErrorTypeToStringMap;
use pocketmine\item\Bow;
use pocketmine\item\Durable;
use pocketmine\item\Item;
use pocketmine\item\Releasable;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\thread\ThreadCrashInfoFrame;
use pocketmine\world\Position;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use function array_combine;
use function array_map;
use function array_reverse;
use function array_values;
use function bin2hex;
use function chunk_split;
use function class_exists;
use function count;
use function debug_zval_dump;
use function dechex;
use function exec;
use function explode;
use function file;
use function file_exists;
use function file_get_contents;
use function function_exists;
use function get_class;
use function get_current_user;
use function get_loaded_extensions;
use function getenv;
use function gettype;
use function implode;
use function interface_exists;
use function is_a;
use function is_array;
use function is_bool;
use function is_float;
use function is_infinite;
use function is_int;
use function is_nan;
use function is_object;
use function is_string;
use function mb_check_encoding;
use function ob_end_clean;
use function ob_get_contents;
use function ob_start;
use function opcache_get_status;
use function ord;
use function php_uname;
use function phpversion;
use function preg_grep;
use function preg_match;
use function preg_match_all;
use function preg_replace;
use function shell_exec;
use function spl_object_id;
use function str_ends_with;
use function str_pad;
use function str_split;
use function str_starts_with;
use function stripos;
use function strlen;
use function substr;
use function sys_get_temp_dir;
use function trim;
use function xdebug_get_function_stack;
use const PHP_EOL;
use const PHP_INT_MAX;
use const PHP_INT_SIZE;
use const PHP_MAXPATHLEN;
use const STR_PAD_LEFT;
use const STR_PAD_RIGHT;

/**
 * Big collection of functions
 */
final class Utils{
	public const OS_WINDOWS = "win";
	public const OS_IOS = "ios";
	public const OS_MACOS = "mac";
	public const OS_ANDROID = "android";
	public const OS_LINUX = "linux";
	public const OS_BSD = "bsd";
	public const OS_UNKNOWN = "other";

	private static ?string $os = null;
	private static ?UuidInterface $serverUniqueId = null;
	private static ?int $cpuCores = null;

	/**
	 * Returns a readable identifier for the given Closure, including file and line.
	 *
	 * @phpstan-param anyClosure $closure
	 * @throws \ReflectionException
	 */
	public static function getNiceClosureName(\Closure $closure) : string{
		$func = new \ReflectionFunction($closure);
		if(!str_ends_with($func->getName(), '{closure}')){
			//closure wraps a named function, can be done with reflection or fromCallable()
			//isClosure() is useless here because it just tells us if $func is reflecting a Closure object

			$scope = $func->getClosureScopeClass();
			if($scope !== null){ //class method
				return
					$scope->getName() .
					($func->getClosureThis() !== null ? "->" : "::") .
					$func->getName(); //name doesn't include class in this case
			}

			//non-class function
			return $func->getName();
		}
		$filename = $func->getFileName();

		return "closure@" . ($filename !== false ?
				Filesystem::cleanPath($filename) . "#L" . $func->getStartLine() :
				"internal"
			);
	}

	/**
	 * Returns a readable identifier for the class of the given object. Sanitizes class names for anonymous classes.
	 *
	 * @throws \ReflectionException
	 */
	public static function getNiceClassName(object $obj) : string{
		$reflect = new \ReflectionClass($obj);
		if($reflect->isAnonymous()){
			$filename = $reflect->getFileName();

			return "anonymous@" . ($filename !== false ?
					Filesystem::cleanPath($filename) . "#L" . $reflect->getStartLine() :
					"internal"
				);
		}

		return $reflect->getName();
	}

	/**
	 * @phpstan-return \Closure(object) : object
	 */
	public static function cloneCallback() : \Closure{
		return static function(object $o){
			return clone $o;
		};
	}

	/**
	 * @phpstan-template TKey of array-key
	 * @phpstan-template TValue of object
	 *
	 * @param object[] $array
	 * @phpstan-param array<TKey, TValue> $array
	 *
	 * @return object[]
	 * @phpstan-return array<TKey, TValue>
	 */
	public static function cloneObjectArray(array $array) : array{
		/** @phpstan-var \Closure(TValue) : TValue $callback */
		$callback = self::cloneCallback();
		return array_map($callback, $array);
	}

	/**
	 * Gets this machine / server instance unique ID
	 * Returns a hash, the first 32 characters (or 16 if raw)
	 * will be an identifier that won't change frequently.
	 * The rest of the hash will change depending on other factors.
	 *
	 * @param string $extra optional, additional data to identify the machine
	 */
	public static function getMachineUniqueId(string $extra = "") : UuidInterface{
		if(self::$serverUniqueId !== null && $extra === ""){
			return self::$serverUniqueId;
		}

		$machine = php_uname("a");
		$cpuinfo = @file("/proc/cpuinfo");
		if($cpuinfo !== false){
			$cpuinfoLines = preg_grep("/(model name|Processor|Serial)/", $cpuinfo);
			if($cpuinfoLines === false){
				throw new AssumptionFailedError("Pattern is valid, so this shouldn't fail ...");
			}
			$machine .= implode("", $cpuinfoLines);
		}
		$machine .= sys_get_temp_dir();
		$machine .= $extra;
		$os = Utils::getOS();
		if($os === Utils::OS_WINDOWS){
			@exec("ipconfig /ALL", $mac);
			$mac = implode("\n", $mac);
			if(preg_match_all("#Physical Address[. ]{1,}: ([0-9A-F\\-]{17})#", $mac, $matches) > 0){
				foreach($matches[1] as $i => $v){
					if($v == "00-00-00-00-00-00"){
						unset($matches[1][$i]);
					}
				}
				$machine .= implode(" ", $matches[1]); //Mac Addresses
			}
		}elseif($os === Utils::OS_LINUX){
			if(file_exists("/etc/machine-id")){
				$machine .= file_get_contents("/etc/machine-id");
			}else{
				@exec("ifconfig 2>/dev/null", $mac);
				$mac = implode("\n", $mac);
				if(preg_match_all("#HWaddr[ \t]{1,}([0-9a-f:]{17})#", $mac, $matches) > 0){
					foreach($matches[1] as $i => $v){
						if($v == "00:00:00:00:00:00"){
							unset($matches[1][$i]);
						}
					}
					$machine .= implode(" ", $matches[1]); //Mac Addresses
				}
			}
		}elseif($os === Utils::OS_ANDROID){
			$machine .= @file_get_contents("/system/build.prop");
		}elseif($os === Utils::OS_MACOS){
			$machine .= shell_exec("system_profiler SPHardwareDataType | grep UUID");
		}
		$data = $machine . PHP_MAXPATHLEN;
		$data .= PHP_INT_MAX;
		$data .= PHP_INT_SIZE;
		$data .= get_current_user();
		foreach(get_loaded_extensions() as $ext){
			$data .= $ext . ":" . phpversion($ext);
		}

		//TODO: use of NIL as namespace is a hack; it works for now, but we should have a proper namespace UUID
		$uuid = Uuid::uuid3(Uuid::NIL, $data);

		if($extra === ""){
			self::$serverUniqueId = $uuid;
		}

		return $uuid;
	}

	/**
	 * Returns the current Operating System
	 * Windows => win
	 * MacOS => mac
	 * iOS => ios
	 * Android => android
	 * Linux => Linux
	 * BSD => bsd
	 * Other => other
	 */
	public static function getOS(bool $recalculate = false) : string{
		if(self::$os === null || $recalculate){
			$uname = php_uname("s");
			if(stripos($uname, "Darwin") !== false){
				if(str_starts_with(php_uname("m"), "iP")){
					self::$os = self::OS_IOS;
				}else{
					self::$os = self::OS_MACOS;
				}
			}elseif(stripos($uname, "Win") !== false || $uname === "Msys"){
				self::$os = self::OS_WINDOWS;
			}elseif(stripos($uname, "Linux") !== false){
				if(@file_exists("/system/build.prop")){
					self::$os = self::OS_ANDROID;
				}else{
					self::$os = self::OS_LINUX;
				}
			}elseif(stripos($uname, "BSD") !== false || $uname === "DragonFly"){
				self::$os = self::OS_BSD;
			}else{
				self::$os = self::OS_UNKNOWN;
			}
		}

		return self::$os;
	}

	public static function getCoreCount(bool $recalculate = false) : int{
		if(self::$cpuCores !== null && !$recalculate){
			return self::$cpuCores;
		}

		$processors = 0;
		switch(Utils::getOS()){
			case Utils::OS_LINUX:
			case Utils::OS_ANDROID:
				if(($cpuinfo = @file('/proc/cpuinfo')) !== false){
					foreach($cpuinfo as $l){
						if(preg_match('/^processor[ \t]*:[ \t]*[0-9]+$/m', $l) > 0){
							++$processors;
						}
					}
				}elseif(($cpuPresent = @file_get_contents("/sys/devices/system/cpu/present")) !== false){
					if(preg_match("/^([0-9]+)\\-([0-9]+)$/", trim($cpuPresent), $matches) > 0){
						$processors = ((int) $matches[2]) - ((int) $matches[1]);
					}
				}
				break;
			case Utils::OS_BSD:
			case Utils::OS_MACOS:
				$processors = (int) shell_exec("sysctl -n hw.ncpu");
				break;
			case Utils::OS_WINDOWS:
				$processors = (int) getenv("NUMBER_OF_PROCESSORS");
				break;
		}
		return self::$cpuCores = $processors;
	}

	/**
	 * Returns a prettified hexdump
	 */
	public static function hexdump(string $bin) : string{
		$output = "";
		$bin = str_split($bin, 16);
		foreach($bin as $counter => $line){
			$hex = chunk_split(chunk_split(str_pad(bin2hex($line), 32, " ", STR_PAD_RIGHT), 2, " "), 24, " ");
			$ascii = preg_replace('#([^\x20-\x7E])#', ".", $line);
			$output .= str_pad(dechex($counter << 4), 4, "0", STR_PAD_LEFT) . "  " . $hex . " " . $ascii . PHP_EOL;
		}

		return $output;
	}

	/**
	 * Returns a string that can be printed, replaces non-printable characters
	 */
	public static function printable(mixed $str) : string{
		if(!is_string($str)){
			return gettype($str);
		}

		return preg_replace('#([^\x20-\x7E])#', '.', $str);
	}

	public static function javaStringHash(string $string) : int{
		$hash = 0;
		for($i = 0, $len = strlen($string); $i < $len; $i++){
			$ord = ord($string[$i]);
			if(($ord & 0x80) !== 0){
				$ord -= 0x100;
			}
			$hash = 31 * $hash + $ord;
			$hash &= 0xFFFFFFFF;
		}
		return $hash;
	}

	public static function getReferenceCount(object $value, bool $includeCurrent = true) : int{
		ob_start();
		debug_zval_dump($value);
		$contents = ob_get_contents();
		if($contents === false) throw new AssumptionFailedError("ob_get_contents() should never return false here");
		$ret = explode("\n", $contents);
		ob_end_clean();

		if(preg_match('/^.* refcount\\(([0-9]+)\\)\\{$/', trim($ret[0]), $m) > 0){
			return ((int) $m[1]) - ($includeCurrent ? 3 : 4); //$value + zval call + extra call
		}
		return -1;
	}

	private static function printableExceptionMessage(\Throwable $e) : string{
		$errstr = preg_replace('/\s+/', ' ', trim($e->getMessage()));

		$errno = $e->getCode();
		if(is_int($errno)){
			try{
				$errno = ErrorTypeToStringMap::get($errno);
			}catch(InvalidArgumentException $ex){
				//pass
			}
		}

		$errfile = Filesystem::cleanPath($e->getFile());
		$errline = $e->getLine();

		return get_class($e) . ": \"$errstr\" ($errno) in \"$errfile\" at line $errline";
	}

	/**
	 * @param mixed[] $trace
	 * @return string[]
	 */
	public static function printableExceptionInfo(\Throwable $e, $trace = null) : array{
		if($trace === null){
			$trace = $e->getTrace();
		}

		$lines = [self::printableExceptionMessage($e)];
		$lines[] = "--- Stack trace ---";
		foreach(Utils::printableTrace($trace) as $line){
			$lines[] = "  " . $line;
		}
		for($prev = $e->getPrevious(); $prev !== null; $prev = $prev->getPrevious()){
			$lines[] = "--- Previous ---";
			$lines[] = self::printableExceptionMessage($prev);
			foreach(Utils::printableTrace($prev->getTrace()) as $line){
				$lines[] = "  " . $line;
			}
		}
		$lines[] = "--- End of exception information ---";
		return $lines;
	}

	private static function stringifyValueForTrace(mixed $value, int $maxStringLength) : string{
		return match(true){
			is_object($value) => "object " . self::getNiceClassName($value) . "#" . spl_object_id($value),
			is_array($value) => "array[" . count($value) . "]",
			is_string($value) => "string[" . strlen($value) . "] " . substr(Utils::printable($value), 0, $maxStringLength),
			is_bool($value) => $value ? "true" : "false",
			is_int($value) => "int " . $value,
			is_float($value) => "float " . $value,
			$value === null => "null",
			default => gettype($value) . " " . Utils::printable((string) $value)
		};
	}

	/**
	 * @param mixed[][] $trace
	 * @phpstan-param list<array<string, mixed>> $trace
	 *
	 * @return string[]
	 */
	public static function printableTrace(array $trace, int $maxStringLength = 80) : array{
		$messages = [];
		for($i = 0; isset($trace[$i]); ++$i){
			$params = "";
			if(isset($trace[$i]["args"]) || isset($trace[$i]["params"])){
				if(isset($trace[$i]["args"])){
					$args = $trace[$i]["args"];
				}else{
					$args = $trace[$i]["params"];
				}
				/** @var mixed[] $args */

				$paramsList = [];
				$offset = 0;
				foreach($args as $argId => $value){
					$paramsList[] = ($argId === $offset ? "" : "$argId: ") . self::stringifyValueForTrace($value, $maxStringLength);
					$offset++;
				}
				$params = implode(", ", $paramsList);
			}
			$messages[] = "#$i " . (isset($trace[$i]["file"]) ? Filesystem::cleanPath($trace[$i]["file"]) : "") . "(" . (isset($trace[$i]["line"]) ? $trace[$i]["line"] : "") . "): " . (isset($trace[$i]["class"]) ? $trace[$i]["class"] . (($trace[$i]["type"] === "dynamic" || $trace[$i]["type"] === "->") ? "->" : "::") : "") . $trace[$i]["function"] . "(" . Utils::printable($params) . ")";
		}
		return $messages;
	}

	/**
	 * Similar to {@link Utils::printableTrace()}, but associates metadata such as file and line number with each frame.
	 * This is used to transmit thread-safe information about crash traces to the main thread when a thread crashes.
	 *
	 * @param mixed[][] $rawTrace
	 * @phpstan-param list<array<string, mixed>> $rawTrace
	 *
	 * @return ThreadCrashInfoFrame[]
	 */
	public static function printableTraceWithMetadata(array $rawTrace, int $maxStringLength = 80) : array{
		$printableTrace = self::printableTrace($rawTrace, $maxStringLength);
		$safeTrace = [];
		foreach($printableTrace as $frameId => $printableFrame){
			$rawFrame = $rawTrace[$frameId];
			$safeTrace[$frameId] = new ThreadCrashInfoFrame(
				$printableFrame,
				$rawFrame["file"] ?? "unknown",
				$rawFrame["line"] ?? 0
			);
		}

		return $safeTrace;
	}

	/**
	 * @return mixed[][]
	 * @phpstan-return list<array<string, mixed>>
	 */
	public static function currentTrace(int $skipFrames = 0) : array{
		++$skipFrames; //omit this frame from trace, in addition to other skipped frames
		if(function_exists("xdebug_get_function_stack") && count($trace = @xdebug_get_function_stack()) !== 0){
			$trace = array_reverse($trace);
		}else{
			$e = new \Exception();
			$trace = $e->getTrace();
		}
		for($i = 0; $i < $skipFrames; ++$i){
			unset($trace[$i]);
		}
		return array_values($trace);
	}

	/**
	 * @return string[]
	 */
	public static function printableCurrentTrace(int $skipFrames = 0) : array{
		return self::printableTrace(self::currentTrace(++$skipFrames));
	}

	/**
	 * Extracts one-line tags from the doc-comment
	 *
	 * @return string[] an array of tagName => tag value. If the tag has no value, an empty string is used as the value.
	 */
	public static function parseDocComment(string $docComment) : array{
		$rawDocComment = substr($docComment, 3, -2); //remove the opening and closing markers
		preg_match_all('/(*ANYCRLF)^[\t ]*(?:\* )?@([a-zA-Z\-]+)(?:[\t ]+(.+?))?[\t ]*$/m', $rawDocComment, $matches);

		return array_combine($matches[1], $matches[2]);
	}

	/**
	 * @phpstan-param class-string $className
	 * @phpstan-param class-string $baseName
	 */
	public static function testValidInstance(string $className, string $baseName) : void{
		$baseInterface = false;
		if(!class_exists($baseName)){
			if(!interface_exists($baseName)){
				throw new InvalidArgumentException("Base class $baseName does not exist");
			}
			$baseInterface = true;
		}
		if(!class_exists($className)){
			throw new InvalidArgumentException("Class $className does not exist or is not a class");
		}
		if(!is_a($className, $baseName, true)){
			throw new InvalidArgumentException("Class $className does not " . ($baseInterface ? "implement" : "extend") . " $baseName");
		}
		$class = new \ReflectionClass($className);
		if(!$class->isInstantiable()){
			throw new InvalidArgumentException("Class $className cannot be constructed");
		}
	}

	/**
	 * Verifies that the given callable is compatible with the desired signature. Throws a TypeError if they are
	 * incompatible.
	 *
	 * @param callable|CallbackType $signature Dummy callable with the required parameters and return type
	 * @param callable              $subject   Callable to check the signature of
	 * @phpstan-param anyCallable|CallbackType $signature
	 * @phpstan-param anyCallable              $subject
	 *
	 * @throws \DaveRandom\CallbackValidator\InvalidCallbackException
	 * @throws \TypeError
	 */
	public static function validateCallableSignature(callable|CallbackType $signature, callable $subject) : void{
		if(!($signature instanceof CallbackType)){
			$signature = CallbackType::createFromCallable($signature);
		}
		if(!$signature->isSatisfiedBy($subject)){
			throw new \TypeError("Declaration of callable `" . CallbackType::createFromCallable($subject) . "` must be compatible with `" . $signature . "`");
		}
	}

	/**
	 * @phpstan-template TMemberType
	 * @phpstan-param array<mixed, TMemberType> $array
	 * @phpstan-param \Closure(TMemberType) : void $validator
	 */
	public static function validateArrayValueType(array $array, \Closure $validator) : void{
		foreach($array as $k => $v){
			try{
				$validator($v);
			}catch(\TypeError $e){
				throw new \TypeError("Incorrect type of element at \"$k\": " . $e->getMessage(), 0, $e);
			}
		}
	}

	/**
	 * Generator which forces array keys to string during iteration.
	 * This is necessary because PHP has an anti-feature where it casts numeric string keys to integers, leading to
	 * various crashes.
	 *
	 * @phpstan-template TKeyType of string
	 * @phpstan-template TValueType
	 * @phpstan-param array<TKeyType, TValueType> $array
	 * @phpstan-return Generator<TKeyType, TValueType, void, void>
	 */
	public static function stringifyKeys(array $array) : Generator{
		foreach($array as $key => $value){ // @phpstan-ignore-line - this is where we fix the stupid bullshit with array keys :)
			yield (string) $key => $value;
		}
	}

	public static function checkUTF8(string $string) : void{
		if(!mb_check_encoding($string, 'UTF-8')){
			throw new InvalidArgumentException("Text must be valid UTF-8");
		}
	}

	/**
	 * @phpstan-template TValue
	 * @phpstan-param TValue|false $value
	 * @phpstan-param string|\Closure() : string $context
	 * @phpstan-return TValue
	 */
	public static function assumeNotFalse(mixed $value, \Closure|string $context = "This should never be false") : mixed{
		if($value === false){
			throw new AssumptionFailedError("Assumption failure: " . (is_string($context) ? $context : $context()) . " (THIS IS A BUG)");
		}
		return $value;
	}

	public static function checkFloatNotInfOrNaN(string $name, float $float) : void{
		if(is_nan($float)){
			throw new InvalidArgumentException("$name cannot be NaN");
		}
		if(is_infinite($float)){
			throw new InvalidArgumentException("$name cannot be infinite");
		}
	}

	public static function checkVector3NotInfOrNaN(Vector3 $vector3) : void{
		if($vector3 instanceof Location){ //location could be masquerading as vector3
			self::checkFloatNotInfOrNaN("yaw", $vector3->yaw);
			self::checkFloatNotInfOrNaN("pitch", $vector3->pitch);
		}
		self::checkFloatNotInfOrNaN("x", $vector3->x);
		self::checkFloatNotInfOrNaN("y", $vector3->y);
		self::checkFloatNotInfOrNaN("z", $vector3->z);
	}

	public static function checkLocationNotInfOrNaN(Location $location) : void{
		self::checkVector3NotInfOrNaN($location);
	}

	/**
	 * Returns an integer describing the current OPcache JIT setting.
	 * @see https://www.php.net/manual/en/opcache.configuration.php#ini.opcache.jit
	 */
	public static function getOpcacheJitMode() : ?int{
		if(
			function_exists('opcache_get_status') &&
			($opcacheStatus = opcache_get_status(false)) !== false &&
			isset($opcacheStatus["jit"]["on"])
		){
			$jit = $opcacheStatus["jit"];
			if($jit["on"] === true){
				return (($jit["opt_flags"] >> 2) * 1000) +
					(($jit["opt_flags"] & 0x03) * 100) +
					($jit["kind"] * 10) +
					$jit["opt_level"];
			}

			//jit available, but disabled
			return 0;
		}

		//jit not available
		return null;
	}

	public static function clamp(float $value, float $minValue, float $maxValue) : float {
		return max($minValue, min($maxValue, $value));
	}

	public static function wrapDegrees(float $degrees) : float {
		$result = fmod($degrees, 360);
		if ($result >= 180) {
			$result -= 360;
		}
		if ($result < -180) {
			$result += 360;
		}
		return $result;
	}

	public static function degreesDifference(float $degrees1, float $degrees2) : float {
		return self::wrapDegrees($degrees2 - $degrees1);
	}

	public static function getDefaultProjectileRange(Releasable $item) : int {
		if ($item instanceof Bow) {
			return 15;
		}
		return 8;
	}

	public static function rotateIfNecessary(float $currentDegrees, float $targetDegrees, float $maxDifference) : float {
		return $targetDegrees - self::clamp(self::degreesDifference($currentDegrees, $targetDegrees), -$maxDifference, $maxDifference);
	}

	public static function isPathfindable(Block $block, PathComputationType $pathType) : bool{
		if ($block instanceof Door) {
			if ($pathType->equals(PathComputationType::LAND()) || $pathType->equals(PathComputationType::AIR())) {
				return $block->isOpen();
			}
			return false;
		} elseif ($block instanceof Slab) {
			//TODO: Waterlogging check
			return false;
		}

		switch ($block->getTypeId()) {
			case BlockTypeIds::ANVIL:
			case BlockTypeIds::BREWING_STAND:
			case BlockTypeIds::DRAGON_EGG:
				//TODO: respawn anchor
			case BlockTypeIds::END_ROD:
				//TODO: lightning rod
				//TODO: piston arm
				return false;

			case BlockTypeIds::DEAD_BUSH:
				return $pathType->equals(PathComputationType::AIR()) || self::getDefaultPathfindable($block, $pathType);

			default:
				return self::getDefaultPathfindable($block, $pathType);

		}
	}

	private static function getDefaultPathfindable(Block $block, PathComputationType $pathType) : bool{
		return match(true){
			$pathType->equals(PathComputationType::LAND()) => !$block->isFullCube(),
			$pathType->equals(PathComputationType::WATER()) => $block instanceof Water, //TODO: watterlogging check
			$pathType->equals(PathComputationType::AIR()) => !$block->isFullCube(),
			default => false
		};
	}

	public static function arrayContains(object $needle, array $array) : bool{
		$useEquals = method_exists($needle, "equals");
		foreach ($array as $value) {
			if (!$value instanceof $needle) {
				continue;
			}
			if ($useEquals) {
				if ($needle->equals($value)) {
					return true;
				}
			} elseif ($needle === $value) {
				return true;
			}
		}

		return false;
	}

	public static function getNearestPlayer(Living $entity, float $maxDistance = -1, ?TargetingConditions $conditions = null) : ?Player{
		$pos = $entity->getPosition();
		return array_reduce($pos->getWorld()->getPlayers(), function(?Player $carry, Player $current) use ($entity, $pos, $maxDistance, $conditions) : ?Player{
			if ($conditions !== null && !$conditions->test($entity, $current)) {
				return $carry;
			}

			$distanceSquared = $current->getPosition()->distanceSquared($pos);
			if ($maxDistance > 0 && $distanceSquared > ($maxDistance ** 2)) {
				return $carry;
			}

			if ($carry === null) {
				return $current;
			}

			return $carry->getPosition()->distanceSquared($pos) < $distanceSquared ? $carry : $current;
		});
	}

	public static function movementInputToMotion(Vector3 $movementInput, float $yaw, float $speed) : Vector3{
		$length = $movementInput->lengthSquared();
		if ($length < 1.0E-7) {
			return Vector3::zero();
		}

		$vec3 = (($length > 1) ? $movementInput->normalize() : $movementInput)->multiply($speed);
		$f = sin($yaw * (M_PI / 180));
		$g = cos($yaw * (M_PI / 180));
		return new Vector3(
			$vec3->x * $g - $vec3->z * $f,
			$vec3->y,
			$vec3->z * $g + $vec3->x * $f
		);
	}

	public static function popItemInHand(Player $player, int $amount = 1) : void{
		if ($player->hasFiniteResources()) {
			$item = $player->getInventory()->getItemInHand();
			$item->pop($amount);

			if ($item->isNull()) {
				$item = VanillaItems::AIR();
			}

			$player->getInventory()->setItemInHand($item);
		}
	}

	public static function transformItemInHand(Player $player, Item $result) : void{
		if ($player->hasFiniteResources()) {
			$item = $player->getInventory()->getItemInHand();
			$item->pop($result->getCount());

			if ($item->isNull()) {
				$player->getInventory()->setItemInHand($result);
				return;
			}

			$player->getInventory()->setItemInHand($item);
		}

		$player->getInventory()->addItem($result);
	}

	public static function damageItemInHand(Player $player, int $amount = 1) : void{
		if ($player->hasFiniteResources()) {
			$item = $player->getInventory()->getItemInHand();
			if ($item instanceof Durable) {
				$item->applyDamage($amount);

				if ($item->isNull()) {
					$item = VanillaItems::AIR();
				}

				$player->getInventory()->setItemInHand($item);
			}
		}
	}

	/**
	 * Generate adjacent positions in a sphere around the given starting position within specified maximum distances in each axis.
	 *
	 * The function uses a generator to efficiently compute and yield adjacent positions in a sphere shape
	 * around the starting position. It calculates positions within the maximum distances specified along the X, Y, and Z axes.
	 *
	 * @param Vector3 $startingPosition The starting position around which the adjacent positions are generated.
	 * @param int     $maxDistanceX     The maximum distance in the X-axis from the starting position.
	 * @param int     $maxDistanceY     The maximum distance in the Y-axis from the starting position.
	 * @param int     $maxDistanceZ     The maximum distance in the Z-axis from the starting position.
	 *
	 * @return Vector[]|Generator The function returns a generator that yields Vector3 objects representing
	 *                             adjacent positions within the specified sphere shape around the starting position.
	 *
	 * @phpstan-return Generator<int, Vector3, void, void>
	 */
	public static function getAdjacentPositions(Vector3 $startingPosition, int $maxDistanceX, int $maxDistanceY, int $maxDistanceZ) : Generator {
		$totalDistance = $maxDistanceX + $maxDistanceY + $maxDistanceZ;
		$startX = $startingPosition->getX();
		$startY = $startingPosition->getY();
		$startZ = $startingPosition->getZ();

		$cursor = Vector3::zero();
		$currentDistance = 0;
		$maxX = 0;
		$maxY = 0;
		$x = 0;
		$y = 0;
		$zMirror = false;

		while (true) {
			if ($zMirror) {
				//Generate positions in the mirrored Z-axis direction
				$zMirror = false;
				$cursor->z = ($startZ - ($cursor->getZ() - $startZ));
				yield clone $cursor;
			} else {
				//Generate positions in the positive X, Y, and Z-axis directions
				$generatedPosition = null;
				while ($generatedPosition === null) {
					if ($y > $maxY) {
						//Update X and Y values for the next iteration
						$x++;
						if ($x > $maxX) {
							$currentDistance++;
							if ($currentDistance > $totalDistance) {
								return; //Finished generating all positions within the sphere
							}

							//Update maxX and reset X for the next distance level
							$maxX = min($maxDistanceX, $currentDistance);
							$x = -$maxX;
						}

						//Update maxY and reset Y for the next distance level
						$maxY = min($maxDistanceY, $currentDistance - abs($x));
						$y = -$maxY;
					}

					//Calculate the current X, Y, and Z coordinates for the position
					$currentX = $x;
					$currentY = $y;
					$currentZ = $currentDistance - abs($currentX) - abs($currentY);

					if ($currentZ <= $maxDistanceZ) {
						//Check if the current position is within the specified maximum distance along the Z-axis
						//If yes, set the zMirror flag if Z is non-zero to ensure symmetry in the sphere generation
						$zMirror = $currentZ !== 0;

						//Generate the position and update the cursor
						$generatedPosition = $cursor = new Vector3($startX + $currentX, $startY + $currentY, $startZ + $currentZ);
					}
					$y++;
				}

				yield clone $generatedPosition;
			}
		}
	}

	public static function getLightLevelDependentMagicValue(Position $pos) : float{
		$lightPercentage = $pos->getWorld()->getFullLight($pos) / 15;

		$ambientLight = 0; //TODO: 0.1 in nether
		return (float) self::lerp($ambientLight, $lightPercentage / (4 - 3 * $lightPercentage), 1);
	}

	public static function signum(int|float $i) : int{
		return $i <=> 0;
	}

	public static function lerp(int|float $start, int|float $end, int|float $t) : int|float {
		return $end + $start * ($t - $end);
	}

	public static function getEntityNameFromId(string $id) : string{
		return ucwords(strtolower(str_replace(["_", "minecraft:"], [" ", ""], trim($id))));
	}
}
