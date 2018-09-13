<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity;

use pocketmine\entity\Living;
use pocketmine\level\Position;

interface CreatureBase {
	/**
	 * @param Position $spawnPos
	 * @param array|null $spawnData
	 *
	 * @return null|Living
	 */
	public static function spawnMob(Position $spawnPos, ?array $spawnData = null): ?Living;

	/**
	 * @return Position|null
	 */
	public function getTarget(): ?Position;
}