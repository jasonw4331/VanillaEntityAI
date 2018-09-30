<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\block;

use jasonwynn10\VanillaEntityAI\entity\hostile\CustomMonster;
use pocketmine\entity\Entity;

class Vine extends \pocketmine\block\Vine {
	/**
	 * @param Entity $entity
	 */
	public function onEntityCollide(Entity $entity) : void {
		parent::onEntityCollide($entity);
		if($entity instanceof CustomMonster) {
			$entity->setMotion($entity->getMotion()->add(0, 0.05));
		}
	}
}