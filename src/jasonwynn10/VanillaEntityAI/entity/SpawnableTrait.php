<?php
namespace jasonwynn10\VanillaEntityAI\entity;

use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\level\Position;
use pocketmine\nbt\tag\CompoundTag;

trait SpawnableTrait {
	protected $spawnLight = 7; // default to monsters
	/**
	 * @param Position $spawnPos
	 * @param null|CompoundTag $spawnData
	 *
	 * @return null|Living
	 */
	public static function spawnFromSpawner(Position $spawnPos, ?CompoundTag $spawnData = null) : ?self {
		$nbt = self::createBaseNBT($spawnPos);
		if(isset($spawnData)) {
			$nbt = $spawnData->merge($nbt);
			$nbt->setInt("id", self::NETWORK_ID);
		}
		/** @var Entity|SpawnableTrait $entity */
		$entity = self::createEntity(self::NETWORK_ID, $spawnPos->level, $nbt);
		if($entity instanceof InventoryHolder) {
			// TODO: randomised armour and items
		}
		// TODO: work on logic here more
		if(!$spawnPos->isValid() or count($entity->getBlocksAround()) > 1 or $spawnPos->level->getFullLight($spawnPos) > $entity->spawnLight) {
			$entity->flagForDespawn();
			return null;
		}else{
			$entity->spawnToAll();
			return $entity;
		}
	}

	/**
	 * @return int
	 */
	public function getSpawnLight(): int {
		return $this->spawnLight;
	}

	/**
	 * @param int $spawnLight
	 *
	 * @return SpawnableTrait
	 */
	public function setSpawnLight(int $spawnLight): SpawnableTrait {
		$this->spawnLight = $spawnLight;
		return $this;
	}
}