<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity;

use pocketmine\entity\Living;
use pocketmine\level\Position;
use pocketmine\nbt\tag\CompoundTag;

interface CreatureBase {
	/**
	 * @param Position $spawnPos
	 * @param CompoundTag|null $spawnData
	 *
	 * @return null|Living
	 */
	public static function spawnMob(Position $spawnPos, ?CompoundTag $spawnData = null) : ?Living;

	/**
	 * @return Position|null
	 */
	public function getTarget(): ?Position;
}