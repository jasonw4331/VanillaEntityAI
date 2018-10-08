<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity;

trait AgeableTrait {
	/** @var bool $baby */
	protected $baby = false;

	public function initEntity() : void {
		if($this->getGenericFlag(self::DATA_FLAG_BABY)) {
			$this->setBaby();
		}
		parent::initEntity();
	}

	/**
	 * @return bool
	 */
	public function isBaby() : bool {
		return $this->baby;
	}

	/**
	 * @param bool $baby
	 *
	 * @return self
	 */
	public function setBaby(bool $baby = true) : self {
		$this->baby = $baby;
		$this->setDataFlag(self::DATA_FLAGS, self::DATA_FLAG_BABY, $baby);
		$this->setSprinting();
		$this->setScale($baby ? 0.5 : 1);
		return $this;
	}
}