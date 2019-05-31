<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity;

use pocketmine\item\Item;

interface InventoryHolder {
	/**
	 * @return bool
	 */
	public function isDropAll() : bool;

	/**
	 * @param bool $dropAll
	 */
	public function setDropAll(bool $dropAll = true);

	public function equipRandomItems() : void;

	public function equipRandomArmour() : void;

	/**
	 * @param Item $item
	 *
	 * @return bool
	 */
	public function checkItemValueToMainHand(Item $item) : bool;

	/**
	 * @param Item $item
	 *
	 * @return bool
	 */
	public function checkItemValueToOffHand(Item $item) : bool;

	/**
	 * @return Item|null
	 */
	public function getMainHand() : ?Item;

	/**
	 * @return Item|null
	 */
	public function getOffHand() : ?Item;
}