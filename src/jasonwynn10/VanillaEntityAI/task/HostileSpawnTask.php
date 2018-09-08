<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\task;

use jasonwynn10\VanillaEntityAI\data\BiomeInfo;
use jasonwynn10\VanillaEntityAI\data\Data;
use jasonwynn10\VanillaEntityAI\data\MobTypeMaps;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\entity\Monster;
use pocketmine\level\biome\Biome;
use pocketmine\level\format\Chunk;
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
					if($entity instanceof Monster and !$entity instanceof Human)
						$entities += 1;
				}
			}
			if($entities >= $cap) {
				return;
			}
			foreach($chunks as $chunk) {
				$packCenter = new Vector3(mt_rand($chunk->getX() << 4, (($chunk->getX() << 4) + 15)), mt_rand(0, $level->getWorldHeight()-1), mt_rand($chunk->getZ() << 4, (($chunk->getZ() << 4) + 15)));;
				$lightLevel = $level->getFullLightAt($packCenter->x, $packCenter->y, $packCenter->z);
				if(!$level->getBlockAt($packCenter->x, $packCenter->y, $packCenter->z)->isSolid() and $lightLevel <= 7) {
					$entityId = Data::NETWORK_IDS[MobTypeMaps::OVERWORLD_HOSTILE_MOBS[array_rand(MobTypeMaps::OVERWORLD_HOSTILE_MOBS)]];
					for($attempts = 0, $currentPackSize = 0; $attempts <= 12 and $currentPackSize < 4; $attempts++) {
						$x = mt_rand(-20, 20) + $packCenter->x;
						$z = mt_rand(-20, 20) + $packCenter->z;
						$pos = new Position($x, $packCenter->y, $z, $level);
						if((!$pos->level->getBlockAt($pos->x, $pos->y - 1, $pos->z)->isTransparent() and ($pos->level->getBlockAt($pos->x, $pos->y, $pos->z)->isTransparent() and $pos->level->getBlockAt($pos->x, $pos->y, $pos->z)->getId() != Block::WATER) and ($pos->level->getBlockAt($pos->x, $pos->y + 1, $pos->z)->isTransparent() and $pos->level->getBlockAt($pos->x, $pos->y + 1, $pos->z)->getId() != Block::WATER)) and $this->isSpawnAllowedByBiome($entityId, $level->getBiomeId($x, $z))) {
							$nbt = Entity::createBaseNBT($pos);
							/** @var Monster $entity */
							$entity = Entity::createEntity($entityId, $level, $nbt);
							if($entity instanceof Monster) {
								$entity->spawnToAll();
								$currentPackSize++;
							}
						}
					}
				}
			}
		}
	}

	/**
	 * @param int $entityId
	 * @param int $trialBiome
	 *
	 * @return bool
	 */
	private function isSpawnAllowedByBiome(int $entityId, int $trialBiome) : bool {
		return (in_array($entityId, BiomeInfo::ALLOWED_ENTITIES_BY_BIOME[$trialBiome]) or (($trialBiome !== Biome::HELL and $trialBiome !== 9) and in_array($entityId, BiomeInfo::OVERWORLD_BIOME_EXEMPT)));
	}
}