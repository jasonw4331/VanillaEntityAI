<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity;

trait PanicableTrait {
	/** @var int $panicTime */
	protected $panicTime = 100;
	/** @var bool $inPanic */
	protected $inPanic = false;

	/**
	 * @param int $tickDiff
	 *
	 * @return bool
	 */
	public function entityBaseTick(int $tickDiff = 1) : bool {
		if($this->panicTime -= $tickDiff <= 0) {
			$this->setPanic(false);
		}
		return parent::entityBaseTick($tickDiff);
	}

	/**
	 * @param bool $panic
	 */
	public function setPanic(bool $panic = true) : void {
		$this->setSprinting($panic);
		$this->inPanic = $panic;
		if($panic) {
			$this->moveTime = 0;
		}
	}

	/**
	 * @return bool
	 */
	public function isInPanic() : bool {
		return $this->inPanic;
	}
}