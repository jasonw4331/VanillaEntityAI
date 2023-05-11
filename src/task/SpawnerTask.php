<?php

declare(strict_types=1);

namespace jasonw4331\VanillaEntityAI\task;

use jasonw4331\VanillaEntityAI\Main;
use jasonw4331\VanillaEntityAI\util\MonsterSpawnerConstants;
use jasonw4331\VanillaEntityAI\util\SpawnVerifier;
use jasonw4331\VanillaEntityAI\util\Utils;
use pocketmine\block\Block;
use pocketmine\block\Flowable;
use pocketmine\block\Opaque;
use pocketmine\block\tile\MonsterSpawner;
use pocketmine\entity\Entity;
use pocketmine\entity\EntityFactory;
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
	 *     "spawnData"?: CompoundTag,
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
		// check tile data has an entity type ready
		if(!isset($this->tileData["entityTypeId"]) || $this->tileData["entityTypeId"] === ":"){
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
					if($block instanceof Opaque and $block->getSide(Facing::UP) instanceof Flowable){ // TODO: account for entity-specific spawn requirements
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
			/** @var Block $spawnBlock */
			$spawnBlock = $spawnSpaces[array_rand($spawnSpaces)];
			$entity = $this->getEntityClassFromTypeId($this->tileData["entityTypeId"]);
			if(SpawnVerifier::canSpawn($entity, $spawnBlock)){
				(new $entity(Location::fromObject($spawnBlock->getPosition()->up(), $spawnBlock->getPosition()->getWorld()), $this->tileData['SpawnData'] ?? null))->spawnToAll();
			}
		}
		// set new spawn delay
		$this->tileData["spawnDelay"] = mt_rand($this->tileData["minSpawnDelay"], $this->tileData["maxSpawnDelay"]);
		// update spawn data if potentials list exists from weighted randomness
		if(isset($this->tileData["spawnPotentials"])){
			$potential = $this->getRandomSpawnPotentials($this->tileData["spawnPotentials"]);
			$this->tileData["entityTypeId"] = $potential->getString(MonsterSpawnerConstants::TAG_SUB_TYPE_ID, ':');
			$this->tileData["spawnData"] = $potential->getCompoundTag(MonsterSpawnerConstants::TAG_SUB_PROPERTIES);
		}
		// schedule next spawn task
		$this->plugin->getScheduler()->scheduleDelayedTask(
			new SpawnerTask($this->plugin, $this->coordinate, $this->tileData),
			$this->tileData["spawnDelay"]
		);
	}

	/**
	 * @phpstan-return class-string<Entity>|null
	 */
	private function getEntityClassFromTypeId(string $entityTypeId) : ?string{
		// drop prefixing
		$entityTypeId = str_replace("minecraft:", "", $entityTypeId);
		// use reflection to acquire registered classes from EntityFactory
		$factory = EntityFactory::getInstance();
		$reflection = new \ReflectionClass($factory);
		$property = $reflection->getProperty('saveNames');
		$property->setAccessible(true);
		/** @phpstan-var array<class-string<Entity>, string> $saveNames */
		$saveNames = $property->getValue($factory);

		// test if entity type id string is similar but not equal to key or value strings
		foreach($saveNames as $class => $saveName){
			if(str_contains(mb_strtolower($saveName), mb_strtolower($entityTypeId))){
				return $class;
			}elseif(str_contains(mb_strtolower($class), mb_strtolower($entityTypeId))){
				return $class;
			}
		}

		return null;
	}

	/**
	 * @phpstan-param ListTag<CompoundTag> $items
	 * @noinspection PhpIncompatibleReturnTypeInspection
	 * @noinspection PhpPossiblePolymorphicInvocationInspection
	 */
	private function getRandomSpawnPotentials(ListTag $items) : CompoundTag {
		$cumulativeWeights = [];
		$totalWeight = 0;
		foreach ($items as $item) {
			$weight = $item->getInt(MonsterSpawnerConstants::TAG_SUB_WEIGHT, 1);
			$totalWeight += $weight;
			$cumulativeWeights[] = $totalWeight; // TODO: check if this is correct
		}
		$index = Utils::binarySearchIntegerArray($cumulativeWeights, mt_rand(1, $totalWeight));
		return $items->get($index);
	}

	public function setEntityId(string $entityId) : void{
		if($entityId === '') {
			$entityId = ':'; // empty string is not allowed by the NBT spec here
		}
		$this->tileData["entityTypeId"] = $entityId;
	}

	public function getEntityId() : string{
		return $this->tileData["entityTypeId"];
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

	public function getEntityWidth() : float{
		return $this->tileData["displayEntityWidth"];
	}

	public function getEntityHeight() : float{
		return $this->tileData["displayEntityHeight"];
	}

	public function getEntityScale() : float{
		return $this->tileData["displayEntityScale"];
	}

	/**
	 * @phpstan-return array{
	 *     "entityTypeId": string,
	 *     "spawnPotentials"?: ListTag,
	 *     "spawnData"?: CompoundTag,
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
	 * }
	 */
	public function getTileData() : array{
		$tileData = $this->tileData;
		if(isset($tileData["spawnPotentials"]))
			$tileData["spawnPotentials"] = clone $tileData["spawnPotentials"];
		if(isset($tileData["spawnData"]))
			$tileData["spawnData"] = clone $tileData["spawnData"];
		return $tileData;
	}
}
