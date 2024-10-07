<?php

namespace pocketmine\item;

class BannerPattern extends Item{

	private BannerPatternType $bannerPatternType;

	public function __construct(ItemIdentifier $identifier, string $name, BannerPatternType $bannerPatternType){
		parent::__construct($identifier, $name);
		$this->bannerPatternType = $bannerPatternType;
	}

	public function getType() : BannerPatternType{
		return $this->bannerPatternType;
	}

}