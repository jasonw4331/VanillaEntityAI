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

	/**
	 * @param Block $block
	 */
	public function onCollideWithBlock(Block $block) : void;

	/**
	 * @param CreatureBase $source
	 */
	public function push(CreatureBase $source) : void;
}