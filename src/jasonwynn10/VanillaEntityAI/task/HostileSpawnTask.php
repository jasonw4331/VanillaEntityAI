<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\task;

use jasonwynn10\VanillaEntityAI\data\Data;
use jasonwynn10\VanillaEntityAI\data\MobTypeMaps;
use jasonwynn10\VanillaEntityAI\entity\hostile\CustomMonster;
use jasonwynn10\VanillaEntityAI\EntityAI;
use pocketmine\entity\Human;
use pocketmine\entity\Monster;
use pocketmine\level\format\Chunk;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class HostileSpawnTask extends Task {
	/**
	 * @param int $currentTick
	 */
	public function onRun(int $currentTick) {
		foreach(Server::getInstance()->getLevels() as $level) {
			if($level->getDifficulty() < Level::DIFFICULTY_EASY) {
				continue;
			}
			/** @var Chunk[] $chunks */
			$chunks = [];
			foreach($level->getPlayers() as $player) {
				$centerX = $player->z >> 4;
				$centerZ = $player->z >> 4;
				for($X = $centerX - 8; $X < $centerX + 8; $X++) {
					for($Z = $centerZ - 8; $Z < $centerZ + 8; $Z++) {
						if(!isset($chunks[$X . ":" . $Z])) {
							$chunks[$X . ":" . $Z] = $level->getChunk($X, $Z, true);
						}
					}
				}
			}
			$cap = 70 * count($chunks) / 256;
			$entities = 0;
			foreach($chunks as $chunk) {
				foreach($chunk->getEntities() as $entity) {
					if($entity instanceof Monster and !$entity instanceof Human) {
						$entities += 1;
					}
				}
			}
			if($entities >= $cap) {
				return;
			}
			foreach($chunks as $chunk) {
				$packCenter = new Vector3(mt_rand($chunk->getX() << 4, (($chunk->getX() << 4) + 15)), mt_rand(0, $level->getWorldHeight() - 1), mt_rand($chunk->getZ() << 4, (($chunk->getZ() << 4) + 15)));
				if(!$level->getBlockAt($packCenter->x, $packCenter->y, $packCenter->z)->isSolid()) {
					$entityId = Data::NETWORK_IDS[MobTypeMaps::OVERWORLD_HOSTILE_MOBS[array_rand(MobTypeMaps::OVERWORLD_HOSTILE_MOBS)]];
					for($attempts = 0, $currentPackSize = 0; $attempts <= 12 and $currentPackSize < 4; $attempts++) {
						$x = mt_rand(-20, 20) + $packCenter->x;
						$z = mt_rand(-20, 20) + $packCenter->z;
						foreach(EntityAI::$entities as $class => $arr) {
							/** @noinspection PhpUndefinedFieldInspection */
							if($class instanceof CustomMonster and $class::NETWORK_ID === $entityId) {
								/** @var CustomMonster $class */
								$entity = $class::spawnMob(new Position($x, $packCenter->y, $z, $level));
								if($entity !== null) {
									$entity->spawnToAll();
									$currentPackSize++;
								}
							}
						}
					}
				}
			}
		}
	}
}