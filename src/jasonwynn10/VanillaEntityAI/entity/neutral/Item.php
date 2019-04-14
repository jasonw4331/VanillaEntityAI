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

	public function entityBaseTick(int $tickDiff = 1) : bool {
		foreach($this->level->getNearbyEntities($this->boundingBox->expandedCopy(1.5,1.5,1.5), $this) as $entity) {
			if($this->pickupDelay === 0 and $entity instanceof Item and $entity->onGround and $this->motion->equals($entity->getMotion()) and $this->item->equals($entity->getItem())) {
				$this->item->setCount($this->item->getCount() + $entity->getItem()->getCount());
				$entity->flagForDespawn();
				foreach($this->getViewers() as $player)
					$this->sendSpawnPacket($player);
				break;
			}
		}
		return parent::entityBaseTick($tickDiff);
	}

	public function onCollideWithEntity(Entity $entity) : void {
		//TODO: minecart interactions
	}

	public function onCollideWithBlock(Block $block) : void {
		// TODO: blocks that delete items
	}

	/**
	 * @param AxisAlignedBB $source
	 */
	public function push(AxisAlignedBB $source) : void { // cannot be pushed
	}
}