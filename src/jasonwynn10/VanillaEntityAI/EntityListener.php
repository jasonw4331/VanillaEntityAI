<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI;

use jasonwynn10\VanillaEntityAI\data\BiomeInfo;
use jasonwynn10\VanillaEntityAI\data\Data;
use jasonwynn10\VanillaEntityAI\data\MobTypeMaps;
use pocketmine\block\Block;
use pocketmine\entity\Creature;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\entity\Monster;
use pocketmine\event\level\ChunkLoadEvent;
use pocketmine\event\level\ChunkUnloadEvent;
use pocketmine\event\Listener;
use pocketmine\level\Position;
use pocketmine\math\Vector3;

class EntityListener implements Listener {

	/** @var EntityAI $plugin */
	private $plugin;

	/**
	 * EntityListener constructor.
	 *
	 * @param EntityAI $plugin
	 */
	public function __construct(EntityAI $plugin) {
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
		$this->plugin = $plugin;
	}

	public function onLoad(ChunkLoadEvent $event) {
		$chunk = $event->getChunk();
		$level = $event->getLevel();
		$packCenter = new Vector3(mt_rand($chunk->getX() << 4, (($chunk->getX() << 4) + 15)), mt_rand(0, 255), mt_rand($chunk->getZ() << 4, (($chunk->getZ() << 4) + 15)));;
		$lightLevel = $level->getFullLightAt($packCenter->x, $packCenter->y, $packCenter->z);
		if(!$level->getBlockAt($packCenter->x, $packCenter->y, $packCenter->z)->isSolid() and $lightLevel > 8) {
			$entityId = Data::NETWORK_IDS[MobTypeMaps::PASSIVE_DRY_MOBS[array_rand(MobTypeMaps::PASSIVE_DRY_MOBS)]];
			for($attempts = 0, $currentPackSize = 0; $attempts <= 12 and $currentPackSize < 4; $attempts++) {
				$x = mt_rand(-20, 20) + $packCenter->x;
				$z = mt_rand(-20, 20) + $packCenter->z;
				$pos = new Position($x, $packCenter->y, $z, $level);
				if((!$pos->level->getBlockAt($pos->x, $pos->y - 1, $pos->z)->isTransparent() and ($pos->level->getBlockAt($pos->x, $pos->y, $pos->z)->isTransparent() and $pos->level->getBlockAt($pos->x, $pos->y, $pos->z)->getId() != Block::WATER) and ($pos->level->getBlockAt($pos->x, $pos->y + 1, $pos->z)->isTransparent() and $pos->level->getBlockAt($pos->x, $pos->y + 1, $pos->z)->getId() != Block::WATER)) and $this->isSpawnAllowedByBiome($entityId, $level->getBiomeId($x, $z))) {
					$nbt = Entity::createBaseNBT($pos);
					/** @var Creature $entity */
					$entity = Entity::createEntity($entityId, $level, $nbt);
					if($entity instanceof Creature) {
						$entity->spawnToAll();
						$currentPackSize++;
					}
				}
			}
		}elseif(!$level->getBlockAt($packCenter->x, $packCenter->y, $packCenter->z)->isSolid() and $lightLevel <= 7){
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

	/**
	 * @param int $entityId
	 * @param int $trialBiome
	 *
	 * @return bool
	 */
	private function isSpawnAllowedByBiome(int $entityId, int $trialBiome) : bool {
		return in_array($entityId, BiomeInfo::ALLOWED_ENTITIES_BY_BIOME[$trialBiome]);
	}

	public function onUnload(ChunkUnloadEvent $event) {
		$chunk = $event->getChunk();
		foreach($chunk->getEntities() as $entity) {
			if($entity instanceof Monster or ($entity instanceof Creature and !$entity instanceof Human)) {
				$entity->flagForDespawn();
				$entity->setCanSaveWithChunk(false); //TODO: check if mob is named or is supposed to not permanently despawn
			}
		}
	}
}