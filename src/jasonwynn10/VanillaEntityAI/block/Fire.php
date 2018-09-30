<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\block;

use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityCombustByBlockEvent;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\Server;

class Fire extends \pocketmine\block\Fire {
	public function onEntityCollide(Entity $entity) : void {
		$ev = new EntityDamageByBlockEvent($this, $entity, EntityDamageEvent::CAUSE_FIRE, 1);
		$entity->attack($ev);

		$ev = new EntityCombustByBlockEvent($this, $entity, 8);
		Server::getInstance()->getPluginManager()->callEvent($ev);
		if(!$ev->isCancelled()) {
			$entity->setOnFire($ev->getDuration());
		}
	}
}