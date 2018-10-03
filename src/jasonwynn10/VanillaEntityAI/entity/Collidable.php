<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\math\AxisAlignedBB;

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
	 * @param AxisAlignedBB $source
	 */
	public function push(AxisAlignedBB $source) : void;
}