<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\neutral;

use jasonwynn10\VanillaEntityAI\entity\Collidable;
use jasonwynn10\VanillaEntityAI\entity\CollisionCheckingTrait;
use jasonwynn10\VanillaEntityAI\entity\InventoryHolder;
use jasonwynn10\VanillaEntityAI\EntityAI;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\entity\object\ItemEntity;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\level\Level;
use pocketmine\math\AxisAlignedBB;
use pocketmine\network\mcpe\protocol\TakeItemEntityPacket;

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
		if($this->pickupDelay !== 0 or !$entity instanceof \pocketmine\inventory\InventoryHolder or !$entity instanceof InventoryHolder or $this->level->getDifficulty() <= Level::DIFFICULTY_EASY) {
			return;
		}
		$chance = EntityAI::getInstance()->getRegionalDifficulty($entity->level, $entity->chunk);
		if($chance < 50) {
			return;
		}
		$item = $this->getItem();
		$inventory = $entity->getInventory();
		if(!$item instanceof Item or !$inventory->canAddItem($item)) {
			return;
		}
		$this->server->getPluginManager()->callEvent($ev = new InventoryPickupItemEvent($inventory, $this));
		if($ev->isCancelled()) {
			return;
		}
		$pk = new TakeItemEntityPacket();
		$pk->eid = $entity->getId();
		$pk->target = $this->getId();
		$this->server->broadcastPacket($this->getViewers(), $pk);
		if($entity instanceof InventoryHolder) {
			$entity->setDropAll();
		}
		$inventory->addItem(clone $item);
		$this->flagForDespawn();
	}

	public function onCollideWithBlock(Block $block): void {
	}

	/**
	 * @param AxisAlignedBB $source
	 */
	public function push(AxisAlignedBB $source): void { // cannot be pushed
	}
}