<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity;
trait AgeableTrait {
	/** @var bool $baby */
	protected $baby = false;

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
		$this->setScale(0.5);
		return $this;
	}
}