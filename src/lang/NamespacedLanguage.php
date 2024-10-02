<?php

namespace pocketmine\lang;

use pocketmine\utils\Utils;
use function explode;
use function in_array;
use function str_starts_with;

final class NamespacedLanguage extends Language{

	/** @var string[] $namespaces */
	private static array $namespaces = ['pocketmine'];

	/**
	 * @return string[]
	 */
	public static function getNamespaces() : array{
		return self::$namespaces;
	}

	public function __construct(private string $namespace, string $lang, ?string $path = null, string $fallbackName = self::FALLBACK_LANGUAGE){
		parent::__construct($lang, $path, $fallbackName);
		// ensure all keys are prefixed with the namespace
		foreach(Utils::stringifyKeys($this->lang) as $key => $value){
			if(!str_starts_with($key, $this->namespace . '.')){
				$this->lang[$namespace . '.' . $key] = $value;
				unset($this->lang[$key]);
			}
		}
		foreach(Utils::stringifyKeys($this->fallbackLang) as $key => $value){
			if(!str_starts_with($key, $this->namespace . '.')){
				$this->fallbackLang[$namespace . '.' . $key] = $value;
				unset($this->fallbackLang[$key]);
			}
		}
	}

	public function merge(Language $language) : void{
		$namespace = "";
		foreach(Utils::stringifyKeys($language->getAll()) as $key => $value){
			if(isset($this->lang[$key])){
				throw new LanguageMismatchException("Duplicate translation key '$key' is not allowed");
			}
			$namespace = explode(".", $key)[0];
			if(in_array($namespace, self::$namespaces, true)){
				throw new LanguageMismatchException("'$namespace' translation namespace is reserved");
			}
		}
		foreach(Utils::stringifyKeys($language->getAllFallback()) as $key => $value){
			if(isset($this->fallbackLang[$key])){
				throw new LanguageMismatchException("Duplicate fallback translation key '$key' is not allowed");
			}
			$namespace = explode(".", $key)[0];
			if(in_array($namespace, self::$namespaces, true)){
				throw new LanguageMismatchException("'$namespace' translation namespace is reserved");
			}
		}
		parent::merge($language);
		self::$namespaces[] = $namespace;
	}

	public function getNamespace() : string{
		return $this->namespace;
	}

}