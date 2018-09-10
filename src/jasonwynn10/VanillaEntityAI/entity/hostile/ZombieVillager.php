<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\hostile;

use jasonwynn10\VanillaEntityAI\inventory\MobInventory;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\Player;

class ZombieVillager extends Zombie implements CustomMonster {
	public const NETWORK_ID = self::ZOMBIE_VILLAGER;

	public $width = 1.031;
	public $height = 2.125;

	/**
	 * @return string
	 */
	public function getName() : string {
		return "Zombie Villager";
	}
}