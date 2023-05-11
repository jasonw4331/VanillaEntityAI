<?php
declare(strict_types=1);

namespace jasonw4331\VanillaEntityAI\entity\trait;

use pocketmine\inventory\SimpleInventory;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;

trait OffHandItemHolderTrait{
	private SimpleInventory $offHandInventory;

	public function __construct(){
		$this->offHandInventory = new SimpleInventory(1); // only needs 1 slot for hand
	}

	public function getOffHandInventory() : SimpleInventory{
		return $this->offHandInventory;
	}

	protected function saveInventoryNBT(CompoundTag $nbt) : CompoundTag{
		$inventoryTag = new ListTag([], NBT::TAG_Compound);
		$nbt->setTag('Offhand', $inventoryTag);
		//Normal inventory
		$slotCount = $this->offHandInventory->getSize() + $this->offHandInventory->getHotbarSize();
		for($slot = $this->offHandInventory->getHotbarSize(); $slot < $slotCount; ++$slot){
			$item = $this->offHandInventory->getItem($slot - 9);
			if(!$item->isNull()){
				$inventoryTag->push($item->nbtSerialize($slot));
			}
		}
		return $nbt;
	}

}