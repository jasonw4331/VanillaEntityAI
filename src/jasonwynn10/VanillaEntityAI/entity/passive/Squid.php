<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\passive;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;

class Squid extends \pocketmine\entity\Squid {
	/**
	 * @param EntityDamageEvent $source
	 */
	public function attack(EntityDamageEvent $source) : void{
		if($source instanceof EntityDamageByEntityEvent and !$this->isUnderwater()) {
			$knockback = $source->getKnockBack();
			$source->setKnockBack($knockback * 0.85);
		}
		parent::attack($source);
	}

	/**
	 * @return int
	 */
	public function getXpDropAmount() : int {
		$exp = parent::getXpDropAmount();
		if(!$this->isBaby()) {
			$exp += mt_rand(1, 3);
			return $exp;
		}
		return $exp;
	}
}