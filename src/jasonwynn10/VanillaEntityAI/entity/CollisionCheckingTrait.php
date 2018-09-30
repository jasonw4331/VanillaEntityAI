<?php
namespace jasonwynn10\VanillaEntityAI\entity;
trait CollisionCheckingTrait {
	/**
	 * @param int $tickDiff
	 *
	 * @return bool
	 */
	public function entityBaseTick(int $tickDiff = 1) : bool {
		$this->checkNearEntities();
		return parent::entityBaseTick($tickDiff);
	}

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