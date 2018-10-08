<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity;

use pocketmine\entity\Ageable;
use pocketmine\event\entity\EntityDamageEvent;

abstract class AnimalBase extends CreatureBase implements Ageable {
	use AgeableTrait, PanicableTrait;
	/** @var int $growTime */
	protected $growTime = 200;

	public function initEntity() : void {
		parent::initEntity();
	}

	/**
	 * @param EntityDamageEvent $source
	 */
	public function attack(EntityDamageEvent $source) : void {
		$this->setPanic();
		parent::attack($source);
	}

	/**
	 * @param int $tickDiff
	 *
	 * @return bool
	 */
	public function entityBaseTick(int $tickDiff = 1) : bool {
		if($this->growTime -= $tickDiff <= 0) {
			$this->setBaby(false);
		}
		return parent::entityBaseTick($tickDiff);
	}
}