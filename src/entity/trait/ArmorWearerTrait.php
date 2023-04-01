<?php

declare(strict_types=1);

namespace jasonwynn10\VanillaEntityAI\entity\trait;

use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;

trait ArmorWearerTrait{

	protected function saveArmorInventory(CompoundTag $nbt) : CompoundTag{
		$inventoryTag = new ListTag([], NBT::TAG_Compound);
		$nbt->setTag('Armor', $inventoryTag);
		//Armor
		for($slot = 0; $slot < 4; ++$slot){
			$item = $this->armorInventory->getItem($slot);
			if(!$item->isNull()){
				$inventoryTag->push($item->nbtSerialize($slot));
			}
		}
		return $nbt;
	}

}
