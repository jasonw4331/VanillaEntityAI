<?php
namespace jasonwynn10\VanillaEntityAI\entity;

use jasonwynn10\VanillaEntityAI\entity\passiveaggressive\Player;
use jasonwynn10\VanillaEntityAI\inventory\MobInventory;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;

trait InventoryHolderTrait {
	/** @var MobInventory $inventory */
	protected $inventory;
	/** @var bool $dropAll */
	protected $dropAll = false;

	public function initEntity() : void {
		$this->inventory = new MobInventory($this, ItemFactory::get(ItemIds::AIR));
		parent::initEntity();
	}

	/**
	 * @return bool
	 */
	public function isDropAll() : bool {
		return $this->dropAll;
	}

	/**
	 * @param bool $dropAll
	 *
	 * @return InventoryHolderTrait
	 */
	public function setDropAll(bool $dropAll = true) {
		$this->dropAll = $dropAll;
		return $this;
	}

	/**
	 * @return MobInventory
	 */
	public function getInventory() : MobInventory {
		return $this->inventory;
	}

	/**
	 * @return array
	 */
	public function getDrops() : array {
		$drops = parent::getDrops();
		if($this->dropAll) {
			$drops = array_merge($drops, $this->inventory->getContents());
		}elseif(mt_rand(1, 1000) <= 85 and !empty($this->inventory->getContents())) {
			$drops[] = $this->inventory->getContents()[array_rand($this->inventory->getContents())];
		}
		return $drops;
	}

	public function close() : void {
		if(!$this->closed) {
			if($this->inventory !== null) {
				$this->inventory->removeAllViewers(true);
				$this->inventory = null;
			}
			parent::close();
		}
	}

	protected function sendSpawnPacket(Player $player) : void {
		parent::sendSpawnPacket($player);
		$pk = new MobEquipmentPacket();
		$pk->entityRuntimeId = $this->getId();
		$pk->item = $this->inventory->getItemInHand();
		$pk->inventorySlot = $pk->hotbarSlot = $this->inventory->getHeldItemIndex();
		$player->dataPacket($pk);
	}
}