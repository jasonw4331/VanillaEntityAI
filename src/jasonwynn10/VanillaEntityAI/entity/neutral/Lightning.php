<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\neutral;

use jasonwynn10\VanillaEntityAI\entity\Collidable;
use jasonwynn10\VanillaEntityAI\entity\CollisionCheckingTrait;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\AxisAlignedBB;

class Lightning extends Entity implements Collidable {
	use CollisionCheckingTrait;
	public function initEntity() : void {
		parent::initEntity(); // TODO: Change the autogenerated stub
	}

	/**
	 * @param int $tickDiff
	 *
	 * @return bool
	 */
	public function entityBaseTick(int $tickDiff = 1) : bool {
		return parent::entityBaseTick($tickDiff); // TODO: Change the autogenerated stub
	}

	/**
	 * @return string
	 */
	public function getName() : string {
		return "Lightning";
	}

	/**
	 * @param Entity $entity
	 */
	public function onCollideWithEntity(Entity $entity) : void {
		// TODO: Implement onCollideWithEntity() method.
		$ev = new EntityDamageEvent($entity, EntityDamageEvent::CAUSE_CUSTOM, 5);
		$entity->attack($ev);
	}

	/**
	 * @param Block $block
	 */
	public function onCollideWithBlock(Block $block) : void {
		// TODO: Implement onCollideWithBlock() method.
	}

	/**
	 * @param AxisAlignedBB $source
	 */
	public function push(AxisAlignedBB $source) : void {
		// TODO: Implement push() method.
	}
}