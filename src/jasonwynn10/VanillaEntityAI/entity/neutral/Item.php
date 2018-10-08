<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\neutral;

use jasonwynn10\VanillaEntityAI\entity\Collidable;
use jasonwynn10\VanillaEntityAI\entity\CollisionCheckingTrait;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\entity\object\ItemEntity;
use pocketmine\math\AxisAlignedBB;

class Item extends ItemEntity implements Collidable {
	use CollisionCheckingTrait;

	public function onCollideWithEntity(Entity $entity) : void {
		if($this->pickupDelay === 0 and $entity instanceof Item and $entity->onGround and mt_rand(1, 50) === 50) { // use randomness for delay before merge
			if($this->item->equals($entity->getItem())) {
				$this->item->setCount($this->item->getCount() + $entity->getItem()->getCount());
			}
			$entity->flagForDespawn();
			foreach($this->getViewers() as $player)
				$this->sendSpawnPacket($player);
			return;
		}
	}

	public function onCollideWithBlock(Block $block) : void {
	}

	/**
	 * @param AxisAlignedBB $source
	 */
	public function push(AxisAlignedBB $source) : void { // cannot be pushed
	}
}