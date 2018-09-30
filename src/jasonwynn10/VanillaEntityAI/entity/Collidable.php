<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity;

use pocketmine\block\Block;
use pocketmine\entity\Entity;

interface Collidable {
	/**
	 * @param Entity $entity
	 */
	public function onCollideWithEntity(Entity $entity) : void;

	public function onCollideWithBlock(Block $block) : void;
}