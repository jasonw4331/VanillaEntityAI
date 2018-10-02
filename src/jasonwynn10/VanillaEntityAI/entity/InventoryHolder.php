<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity;

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
}