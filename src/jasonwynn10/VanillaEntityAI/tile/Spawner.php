<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 9/13/2018
 * Time: 6:28 AM
 */

namespace jasonwynn10\VanillaEntityAI\tile;


use jasonwynn10\VanillaEntityAI\data\Data;
use jasonwynn10\VanillaEntityAI\entity\passiveaggressive\Player;
use jasonwynn10\VanillaEntityAI\EntityAI;
use pocketmine\entity\Entity;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\tile\Spawnable;

class Spawner extends Spawnable {

	protected $entityId = -1;
	protected $spawnRange = 8;
	protected $maxNearbyEntities = 6;
	protected $requiredPlayerRange = 16;

	protected $delay = 0;

	protected $minSpawnDelay = 200;
	protected $maxSpawnDelay = 800;
	protected $spawnCount = 0;

	public function onUpdate() : bool {
		if($this->isClosed()) {
			return false;
		}
		if($this->entityId === -1) {
			return false;
		}

		if($this->delay++ >= mt_rand($this->minSpawnDelay, $this->maxSpawnDelay)) {
			$this->delay = 0;

			$list = [];
			$isValid = false;
			foreach($this->level->getEntities() as $entity) {
				if($entity->distance($this) <= $this->requiredPlayerRange) {
					if($entity instanceof Player) {
						$isValid = true;
					}
					$list[] = $entity;
					break;
				}
			}

			if($isValid and count($list) <= $this->maxNearbyEntities) {
				$y = $this->y;
				$x = $this->x + mt_rand(-$this->spawnRange, $this->spawnRange);
				$z = $this->z + mt_rand(-$this->spawnRange, $this->spawnRange);
				$pos = EntityAI::getSuitableHeightPosition($x, $y, $z, $this->level);
				$pos->y += Data::HEIGHTS[$this->entityId];
				$entity = Entity::createEntity($this->entityId, $this->level, Entity::createBaseNBT($pos));
				if($entity != null) {
					$entity->spawnToAll();
				}
			}
		}
		$this->scheduleUpdate();
		return true;
	}

	/**
	 * An extension to getSpawnCompound() for
	 * further modifying the generic tile NBT.
	 *
	 * @param CompoundTag $nbt
	 */
	protected function addAdditionalSpawnData(CompoundTag $nbt) : void {
		// TODO: Implement addAdditionalSpawnData() method.
	}

	/**
	 * Reads additional data from the CompoundTag on tile creation.
	 *
	 * @param CompoundTag $nbt
	 */
	protected function readSaveData(CompoundTag $nbt) : void {
		// TODO: Implement readSaveData() method.
	}

	/**
	 * Writes additional save data to a CompoundTag, not including generic things like ID and coordinates.
	 *
	 * @param CompoundTag $nbt
	 */
	protected function writeSaveData(CompoundTag $nbt) : void {
		// TODO: Implement writeSaveData() method.
	}
}