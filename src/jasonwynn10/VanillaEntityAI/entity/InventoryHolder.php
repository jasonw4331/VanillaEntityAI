<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity;

use pocketmine\item\Item;

interface InventoryHolder extends \pocketmine\inventory\InventoryHolder {
	/**
	 * @return bool
	 */
	public function isDropAll(): bool;

	/**
	 * @param bool $dropAll
	 */
	public function setDropAll(bool $dropAll = true);

	/**
	 * @return Item[]
	 */
	public function getRandomItems() : array;
}