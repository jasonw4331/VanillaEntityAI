<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity;

use pocketmine\entity\Entity;

interface Collidable {
	public function onCollideWithEntity(Entity $entity): void;
}