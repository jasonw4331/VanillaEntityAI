<?php
declare(strict_types=1);

namespace jasonwynn10\VanillaEntityAI\entity\trait;

use pocketmine\inventory\SimpleInventory;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;

trait ItemHolderTrait{
	private SimpleInventory $inventory;
	private bool $canPickupLoot = true;
	private bool $hasSetCanPickupLoot = true;

	public function __construct(){
		$this->inventory = new SimpleInventory(1); // only needs 1 slot for hand
	}

	public function getInventory() : SimpleInventory{
		return $this->inventory;
	}

	public function getCanPickupLoot() : bool{
		return $this->canPickupLoot;
	}

	public function setCanPickupLoot(bool $canPickupLoot) : void{
		$this->canPickupLoot = $canPickupLoot;
	}

	public function getHasSetCanPickupLoot() : bool{
		return $this->hasSetCanPickupLoot;
	}

	public function setHasSetCanPickupLoot(bool $hasSetCanPickupLoot) : void{
		$this->hasSetCanPickupLoot = $hasSetCanPickupLoot;
	}

	protected function saveInventoryNBT(CompoundTag $nbt) : CompoundTag{
		$inventoryTag = new ListTag([], NBT::TAG_Compound);
		$nbt->setTag('Mainhand', $inventoryTag);
		//Normal inventory
		$slotCount = $this->inventory->getSize() + $this->inventory->getHotbarSize();
		for($slot = $this->inventory->getHotbarSize(); $slot < $slotCount; ++$slot){
			$item = $this->inventory->getItem($slot - 9);
			if(!$item->isNull()){
				$inventoryTag->push($item->nbtSerialize($slot));
			}
		}
		$nbt->setByte('canPickupItems', $this->canPickupLoot ? 1 : 0);
		$nbt->setByte('hasSetCanPickupItems', $this->hasSetCanPickupLoot ? 1 : 0);
		return $nbt;
	}

}