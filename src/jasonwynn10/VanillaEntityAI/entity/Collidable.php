<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity;

use pocketmine\entity\Entity;

interface Collidable {
	/**
	 * @param Entity $entity
	 */
	public function onCollideWithEntity(Entity $entity) : void;
}