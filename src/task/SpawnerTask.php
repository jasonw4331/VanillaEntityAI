<?php

declare(strict_types=1);

namespace jasonwynn10\VanillaEntityAI\task;

use jasonwynn10\VanillaEntityAI\Main;
use pocketmine\block\Flowable;
use pocketmine\block\tile\MonsterSpawner;
use pocketmine\entity\Entity;
use pocketmine\entity\Location;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\world\Position;
use function array_filter;
use function array_rand;
use function count;
use function mt_rand;

final class SpawnerTask extends Task{

	/**
	 * @phpstan-param array{
	 *     "entityTypeId": string,
	 *     "spawnPotentials"?: ListTag,
	 *     "SpawnData"?: CompoundTag,
	 *     "displayEntityWidth": float,
	 *     "displayEntityHeight": float,
	 *     "displayEntityScale": float,
	 *     "spawnDelay": int,
	 *     "minSpawnDelay": int,
	 *     "maxSpawnDelay": int,
	 *     "spawnPerAttempt": int,
	 *     "maxNearbyEntities": int,
	 *     "spawnRange": int,
	 *     "requiredPlayerRange": int
	 * } $tileData
	 */
	public function __construct(private Main $plugin, private Position $coordinate, private array $tileData){}

	public function onRun() : void{
		// check if block still exists
		if(!$this->coordinate->getWorld()->getTileAt((int) $this->coordinate->x, (int) $this->coordinate->y, (int) $this->coordinate->z) instanceof MonsterSpawner){
			return;
		}
		// check tile data has a mob ready
		if(!isset($this->tileData["entityTypeId"])){
			return;
		}
		// check if player is nearby
		$nearby = $this->coordinate->getWorld()->getNearbyEntities(AxisAlignedBB::one()->expand($this->tileData["requiredPlayerRange"], $this->tileData["requiredPlayerRange"], $this->tileData["requiredPlayerRange"]));
		$nearbyPlayers = array_filter($nearby, static fn(Entity $entity) => $entity instanceof Player);
		if(count($nearbyPlayers) === 0){
			return;
		}
		// check mob cap
		$nearbyEntitiesExcludingPlayers = array_filter($nearby, static fn(Entity $entity) => !$entity instanceof Player);
		if(count($nearbyEntitiesExcludingPlayers) >= $this->tileData["maxNearbyEntities"]){
			return;
		}
		// check spawn spaces all have air blocks above them
		$spawnSpaces = [];
		for($x = $this->coordinate->x - $this->tileData["spawnRange"]; $x <= $this->coordinate->x + $this->tileData["spawnRange"]; ++$x){
			for($z = $this->coordinate->z - $this->tileData["spawnRange"]; $z <= $this->coordinate->z + $this->tileData["spawnRange"]; ++$z){
				for($y = $this->coordinate->y - $this->tileData["spawnRange"]; $y <= $this->coordinate->y + $this->tileData["spawnRange"]; ++$y){
					$block = $this->coordinate->getWorld()->getBlockAt((int) $x, (int) $y, (int) $z);
					if($block instanceof Flowable && $block->getSide(Facing::UP) instanceof Flowable){
						$spawnSpaces[] = $block;
					}
				}
			}
		}
		if(count($spawnSpaces) === 0){
			return;
		}
		// spawn mob cluster
		for($i = 0; $i < $this->tileData["spawnPerAttempt"]; $i++){
			$spawnSpace = $spawnSpaces[array_rand($spawnSpaces)]->getPosition();
			$entity = $this->getEntityClassFromTypeId($this->tileData["entityTypeId"]);

			(new $entity(Location::fromObject($spawnSpace, $spawnSpace->getWorld()), $this->tileData['SpawnData'] ?? null))->spawnToAll();
		}

		// set new spawn delay
		$this->tileData["spawnDelay"] = mt_rand($this->tileData["minSpawnDelay"], $this->tileData["maxSpawnDelay"]);

		// schedule next spawn task
		$this->plugin->getScheduler()->scheduleDelayedTask(
			new SpawnerTask($this->plugin, $this->coordinate, $this->tileData),
			$this->tileData["spawnDelay"]
		);
	}

	public function setEntityId(string $entityId) : void{
		$this->tileData["entityTypeId"] = $entityId;
	}

	public function getSpawnDelay() : int{
		return $this->tileData["spawnDelay"];
	}

	public function getMinSpawnDelay() : int{
		return $this->tileData["minSpawnDelay"];
	}

	public function getMaxSpawnDelay() : int{
		return $this->tileData["maxSpawnDelay"];
	}

	public function getSpawnCount() : int{
		return $this->tileData["spawnPerAttempt"];
	}

	public function getMaxNearbyEntities() : int{
		return $this->tileData["maxNearbyEntities"];
	}

	public function getRequiredPlayerRange() : int{
		return $this->tileData["requiredPlayerRange"];
	}

	public function getSpawnRange() : int{
		return $this->tileData["spawnRange"];
	}
}
