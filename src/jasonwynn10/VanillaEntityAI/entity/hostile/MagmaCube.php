<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\hostile;

use jasonwynn10\VanillaEntityAI\entity\CreatureBase;
use pocketmine\level\Position;
use pocketmine\nbt\tag\CompoundTag;

class MagmaCube extends Slime {
	public const NETWORK_ID = self::MAGMA_CUBE;
	public $width = 1.2;
	public $height = 1.2;

	/**
	 * @return string
	 */
	public function getName() : string {
		return "Magma Cube";
	}

	/**
	 * @param Position $spawnPos
	 * @param CompoundTag|null $spawnData
	 *
	 * @return null|CreatureBase
	 */
	public static function spawnMob(Position $spawnPos, ?CompoundTag $spawnData = null) : ?CreatureBase {
		// TODO: Implement spawnMob() method.
	}

	/**
	 * @param Position $spawnPos
	 * @param null|CompoundTag $spawnData
	 *
	 * @return null|CreatureBase
	 */
	public static function spawnFromSpawner(Position $spawnPos, ?CompoundTag $spawnData = null) : ?CreatureBase {
		// TODO: Implement spawnFromSpawner() method.
	}
}