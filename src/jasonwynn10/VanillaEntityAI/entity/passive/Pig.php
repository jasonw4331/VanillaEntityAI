<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\passive;

use jasonwynn10\VanillaEntityAI\EntityAI;
use pocketmine\entity\Creature;
use pocketmine\item\Item;

class Pig extends Creature {
	public function initEntity() {
		$this->setMaxHealth(10);
		parent::initEntity();
	}

	/**
	 * @param int $tickDiff
	 *
	 * @return bool
	 */
	public function entityBaseTick(int $tickDiff = 1) : bool {
		$hasUpdate = parent::entityBaseTick($tickDiff);
		$target = EntityAI::passiveAI($this, $tickDiff);
		// TODO: move to target
		return $hasUpdate;
	}

	/**
	 * @return Item[]
	 */
	public function getDrops() : array {
		$drops = [];
		if($this->isOnFire()) {
			array_pad($drops, mt_rand(1, 3), Item::get(Item::COOKED_PORKCHOP));
		}else{
			array_pad($drops, mt_rand(1, 3), Item::get(Item::RAW_PORKCHOP));
		}
		if(!empty($this->getArmorInventory()->getContents())) {
			array_merge($drops, $this->getArmorInventory()->getContents());
		}
		return $drops;
	}

	public function getXpDropAmount() : int {
		//TODO: check for baby state
		return mt_rand(1, 3);
	}

	/**
	 * @return string
	 */
	public function getName() : string {
		return "Pig";
	}
}