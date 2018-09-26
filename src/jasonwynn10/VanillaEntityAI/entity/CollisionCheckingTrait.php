<?php
namespace jasonwynn10\VanillaEntityAI\entity;
trait CollisionCheckingTrait {
	protected function checkNearEntities() {
		// TODO: better method/logic
		foreach($this->level->getNearbyEntities($this->boundingBox, $this) as $entity) {
			if(!$entity->isAlive() or $entity->isFlaggedForDespawn()) {
				continue;
			}
			$entity->scheduleUpdate();
			if($entity instanceof Collidable) {
				$entity->onCollideWithEntity($this);
			}
		}
	}
}