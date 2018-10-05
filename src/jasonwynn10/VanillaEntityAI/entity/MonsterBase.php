<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity;

use jasonwynn10\VanillaEntityAI\entity\passiveaggressive\Player;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\level\Level;
use pocketmine\level\Position;

abstract class MonsterBase extends CreatureBase {
	public function initEntity() : void {
		parent::initEntity();
	}

	/**
	 * @param EntityDamageEvent $source
	 */
	public function attack(EntityDamageEvent $source) : void {
		if($source instanceof EntityDamageByEntityEvent)
			$this->setTarget($source->getDamager());
		parent::attack($source);
	}

	/**
	 * @param int $tickDiff
	 *
	 * @return bool
	 */
	public function entityBaseTick(int $tickDiff = 1): bool {
		if($this->level->getDifficulty() <= Level::DIFFICULTY_PEACEFUL) {
			$this->flagForDespawn();
		}
		return parent::entityBaseTick($tickDiff);
	}

	/**
	 * @param Position|null $target
	 *
	 * @return bool
	 */
	protected function isTargetValid(?Position $target) : bool {
		if($target instanceof Entity) {
			if($target instanceof Player)
				return !$target->isFlaggedForDespawn() and !$target->isClosed() and $target->isValid() and $target->isAlive() and $target->isSurvival();
			return !$target->isFlaggedForDespawn() and !$target->isClosed() and $target->isValid() and $target->isAlive();
		}else {
			return $target !== null and $target->isValid();
		}
	}
}