<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;

abstract class MonsterBase extends CreatureBase {
	public function attack(EntityDamageEvent $source) : void {
		if($source instanceof EntityDamageByEntityEvent)
			$this->setTarget($source->getDamager());
		parent::attack($source);
	}
}