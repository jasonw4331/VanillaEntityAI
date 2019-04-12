<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\task;

use jasonwynn10\VanillaEntityAI\data\BiomeEntityList;
use jasonwynn10\VanillaEntityAI\entity\AnimalBase;
use jasonwynn10\VanillaEntityAI\EntityAI;
use pocketmine\level\format\Chunk;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class PassiveSpawnTask extends Task {
	/**
	 * @param int $currentTick
	 */
	public function onRun(int $currentTick) {
		foreach(Server::getInstance()->getLevels() as $level) {
			/** @var Chunk[] $chunks */
			$chunks = [];
			foreach($level->getPlayers() as $player) {
				foreach($player->usedChunks as $hash => $sent) {
					if($sent) {
						Level::getXZ($hash, $chunkX, $chunkZ);
						$chunks[$hash] = $player->getLevel()->getChunk($chunkX, $chunkZ, true);
					}
				}
			}
			$entities = 0;
			foreach($chunks as $chunk) {
				foreach($chunk->getEntities() as $entity) {
					if($entity instanceof AnimalBase) {
						$entities += 1;
					}
					if($entities >= 200) { // bedrock edition has hard cap of 200
						return;
					}
				}
			}
			foreach($chunks as $chunk) {
				$packCenter = new Vector3(mt_rand($chunk->getX() << 4, (($chunk->getX() << 4) + 15)), mt_rand(0, $level->getWorldHeight() - 1), mt_rand($chunk->getZ() << 4, (($chunk->getZ() << 4) + 15)));
				$biomeId = $level->getBiomeId($packCenter->x, $packCenter->z);
				$entityList = BiomeEntityList::BIOME_ANIMALS[$biomeId];
				if(empty($entityList))
					continue;
				$entityId = $entityList[array_rand(BiomeEntityList::BIOME_ANIMALS[$biomeId])];
				if(!$level->getBlockAt($packCenter->x, $packCenter->y, $packCenter->z)->isSolid()) {
					for($attempts = 0, $currentPackSize = 0; $attempts <= 12 and $currentPackSize < 4; $attempts++) {
						$x = mt_rand(-20, 20) + $packCenter->x;
						$z = mt_rand(-20, 20) + $packCenter->z;
						foreach(EntityAI::getEntities() as $class => $arr) {
							if($class instanceof AnimalBase and $class::NETWORK_ID === $entityId) {
								$entity = $class::spawnMob(new Position($x + 0.5, $packCenter->y, $z + 0.5, $level));
								if($entity !== null) {
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