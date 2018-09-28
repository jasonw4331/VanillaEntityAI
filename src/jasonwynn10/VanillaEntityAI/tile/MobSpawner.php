<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\tile;

use jasonwynn10\VanillaEntityAI\entity\CreatureBase;
use jasonwynn10\VanillaEntityAI\EntityAI;
use pocketmine\entity\Entity;
use pocketmine\entity\EntityIds;
use pocketmine\entity\Living;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\AxisAlignedBB;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\tile\Spawnable;

class MobSpawner extends Spawnable {
	public const IS_MOVABLE = "isMovable"; // ByteTag
	public const DELAY = "Delay"; // ShortTag
	public const MAX_NEARBY_ENTITIES = "MaxNearbyEntities"; // ShortTag
	public const MAX_SPAWN_DELAY = "MaxSpawnDelay"; // ShortTag
	public const MIN_SPAWN_DELAY = "MinSawnDelay"; // ShortTag
	public const REQUIRED_PLAYER_RANGE = "RequiredPlayerRange"; // ShortTag
	public const SPAWN_COUNT = "SpawnCount"; // ShortTag
	public const SPAWN_RANGE = "SpawnRange"; // ShortTag
	public const ENTITY_ID = "EntityId"; // IntTag
	public const DISPLAY_ENTITY_HEIGHT = "DisplayEntityHeight"; // FloatTag
	public const DISPLAY_ENTITY_SCALE = "DisplayEntityScale"; // FloatTag
	public const DISPLAY_ENTITY_WIDTH = "DisplayEntityWidth"; // FloatTag

	/** @var int $spawnRange */
	protected $spawnRange = 4;
	/** @var int $maxNearbyEntities */
	protected $maxNearbyEntities = 6;
	/** @var int $requiredPlayerRange */
	protected $requiredPlayerRange = 16;
	/** @var int $delay */
	protected $delay = -1;
	/** @var int $minSpawnDelay */
	protected $minSpawnDelay = 200;
	/** @var int $maxSpawnDelay */
	protected $maxSpawnDelay = 800;
	/** @var int $spawnCount */
	protected $spawnCount = 4;
	/** @var AxisAlignedBB|null $spawnArea */
	protected $spawnArea;
	/** @var bool $isMovable */
	protected $isMovable = false;
	/** @var int $entityId */
	protected $entityId = -1;
	/** @var float $displayHeight */
	protected $displayHeight = 0.9;
	/** @var float $displayScale */
	protected $displayScale = 0.5;
	/** @var float $displayWidth */
	protected $displayWidth = 0.3;

	/**
	 * @return bool
	 */
	public function onUpdate(): bool {
		if($this->isClosed() or $this->entityId < EntityIds::CHICKEN) { // TODO are there entities with ids less than 10?
			return false;
		}
		if(--$this->delay === 0) {
			$this->delay = mt_rand($this->minSpawnDelay, $this->maxSpawnDelay);
			$valid = false;
			foreach($this->level->getPlayers() as $player) {
				if($this->distance($player) <= $this->requiredPlayerRange) {
					$valid = true;
					break;
				}
			}
			/**
			 * @var string $class
			 * @var string[] $arr
			 */
			foreach(EntityAI::$entities as $class => $arr) {
				/** @noinspection PhpUndefinedFieldInspection */
				if($class instanceof CreatureBase and $class::NETWORK_ID === $this->entityId) {
					if($valid and count(self::getAreaEntities($this->spawnArea, $this->level, $class)) < $this->maxNearbyEntities) {
						$spawned = 0;
						while($spawned < $this->spawnCount) {
							/** @var CreatureBase $class */
							$entity = $class::spawnMob($this->getRandomSpawnPos());
							if($entity !== null) {
								$spawned++;
							}
						}
					}
				}
			}
		}elseif($this->delay === -1) {
			$this->delay = mt_rand($this->minSpawnDelay, $this->maxSpawnDelay);
			$this->entityId = mt_rand(EntityIds::CHICKEN, EntityIds::FISH);
			$this->onChanged();
		}
		$this->scheduleUpdate();
		return true;
	}

	/**
	 * @param AxisAlignedBB $bb
	 * @param Level $level
	 * @param string $type
	 *
	 * @return array
	 */
	protected static function getAreaEntities(AxisAlignedBB $bb, Level $level, string $type = Living::class) {
		$nearby = [];
		$minX = ((int)floor($bb->minX)) >> 4; // TODO: check if this is right
		$maxX = ((int)floor($bb->maxX)) >> 4;
		$minZ = ((int)floor($bb->minZ)) >> 4;
		$maxZ = ((int)floor($bb->maxZ)) >> 4;
		for($x = $minX; $x <= $maxX; ++$x) {
			for($z = $minZ; $z <= $maxZ; ++$z) {
				foreach($level->getChunkEntities($x, $z) as $entity) {
					/** @var Entity|null $entity */
					if($entity instanceof $type and $entity->boundingBox->intersectsWith($bb)) {
						$nearby[] = $entity;
					}
				}
			}
		}
		return $nearby;
	}

	/**
	 * Returns a randomized position within the spawner spawn range
	 *
	 * @return Position returns valid y coordinate if found
	 */
	protected function getRandomSpawnPos(): Position {
		$x = mt_rand($this->spawnArea->minX, $this->spawnArea->maxX);
		$y = mt_rand($this->spawnArea->minY, $this->spawnArea->maxY);
		$z = mt_rand($this->spawnArea->minZ, $this->spawnArea->maxZ);
		return new Position($x + 0.5, $y, $z + 0.5, $this->level);
	}

	/**
	 * Reads additional data from the CompoundTag on tile creation.
	 *
	 * @param CompoundTag $nbt
	 */
	protected function readSaveData(CompoundTag $nbt): void {
		if($nbt->hasTag(self::ENTITY_ID, IntTag::class)) {
			$this->entityId = $nbt->getInt(self::ENTITY_ID);
		}
		if($nbt->hasTag(self::SPAWN_COUNT, ShortTag::class)) {
			$this->spawnCount = $nbt->getShort(self::SPAWN_COUNT);
		}
		if($nbt->hasTag(self::SPAWN_RANGE, ShortTag::class)) {
			$this->spawnRange = $nbt->getShort(self::SPAWN_RANGE);
		}
		$this->spawnArea = new AxisAlignedBB($this->x - $this->spawnRange, $this->y - 1, $this->z - $this->spawnRange, $this->x + $this->spawnRange, $this->y + 1, $this->z + $this->spawnRange);
		if($nbt->hasTag(self::DELAY, ShortTag::class)) {
			$this->delay = $nbt->getShort(self::DELAY);
		}
		if($nbt->hasTag(self::MIN_SPAWN_DELAY, ShortTag::class)) {
			$this->minSpawnDelay = $nbt->getShort(self::MIN_SPAWN_DELAY);
		}
		if($nbt->hasTag(self::MAX_SPAWN_DELAY, ShortTag::class)) {
			$this->maxSpawnDelay = $nbt->getShort(self::MAX_SPAWN_DELAY);
		}
		if($nbt->hasTag(self::MAX_NEARBY_ENTITIES, ShortTag::class)) {
			$this->maxNearbyEntities = $nbt->getShort(self::MAX_NEARBY_ENTITIES);
		}
		if($nbt->hasTag(self::REQUIRED_PLAYER_RANGE, ShortTag::class)) {
			$this->requiredPlayerRange = $nbt->getShort(self::REQUIRED_PLAYER_RANGE);
		}
		if($nbt->hasTag(self::DISPLAY_ENTITY_HEIGHT, FloatTag::class)) {
			$this->displayHeight = $nbt->getFloat(self::DISPLAY_ENTITY_HEIGHT);
		}
		if($nbt->hasTag(self::DISPLAY_ENTITY_WIDTH, FloatTag::class)) {
			$this->displayHeight = $nbt->getFloat(self::DISPLAY_ENTITY_WIDTH);
		}
		if($nbt->hasTag(self::DISPLAY_ENTITY_SCALE, FloatTag::class)) {
			$this->displayHeight = $nbt->getFloat(self::DISPLAY_ENTITY_SCALE);
		}
	}

	/**
	 * Writes additional save data to a CompoundTag, not including generic things like ID and coordinates.
	 *
	 * @param CompoundTag $nbt
	 */
	protected function writeSaveData(CompoundTag $nbt): void {
		$this->addAdditionalSpawnData($nbt);
	}

	/**
	 * An extension to getSpawnCompound() for
	 * further modifying the generic tile NBT.
	 *
	 * @param CompoundTag $nbt
	 */
	protected function addAdditionalSpawnData(CompoundTag $nbt): void {
		$nbt->setByte(self::IS_MOVABLE, (int)$this->isMovable);
		$nbt->setShort(self::DELAY, $this->delay);
		$nbt->setShort(self::MAX_NEARBY_ENTITIES, $this->maxNearbyEntities);
		$nbt->setShort(self::MAX_SPAWN_DELAY, $this->maxSpawnDelay);
		$nbt->setShort(self::MIN_SPAWN_DELAY, $this->minSpawnDelay);
		$nbt->setShort(self::REQUIRED_PLAYER_RANGE, $this->requiredPlayerRange);
		$nbt->setShort(self::SPAWN_COUNT, $this->spawnCount);
		$nbt->setShort(self::SPAWN_RANGE, $this->spawnRange);
		$nbt->setInt(self::ENTITY_ID, $this->entityId);
		$nbt->setFloat(self::DISPLAY_ENTITY_HEIGHT, $this->displayHeight);
		$nbt->setFloat(self::DISPLAY_ENTITY_WIDTH, $this->displayWidth);
		$nbt->setFloat(self::DISPLAY_ENTITY_SCALE, $this->displayScale);
		$this->scheduleUpdate();
	}

	/**
	 * @return int
	 */
	public function getEntityId(): int {
		return $this->entityId;
	}

	/**
	 * @param int $eid
	 *
	 * @return MobSpawner
	 */
	public function setEntityId(int $eid): MobSpawner {
		$this->entityId = $eid;
		$this->delay = mt_rand($this->minSpawnDelay, $this->maxSpawnDelay);
		$this->onChanged();
		$this->scheduleUpdate();
		return $this;
	}

	/**
	 * @param int $minDelay
	 *
	 * @return MobSpawner
	 */
	public function setMinSpawnDelay(int $minDelay): MobSpawner {
		if($minDelay < $this->maxSpawnDelay) {
			$this->minSpawnDelay = $minDelay;
		}
		return $this;
	}

	/**
	 * @param int $maxDelay
	 *
	 * @return MobSpawner
	 */
	public function setMaxSpawnDelay(int $maxDelay): MobSpawner {
		if($this->minSpawnDelay < $maxDelay and $maxDelay !== 0) {
			$this->maxSpawnDelay = $maxDelay;
		}
		return $this;
	}

	/**
	 * @param int $delay
	 *
	 * @return MobSpawner
	 */
	public function setSpawnDelay(int $delay): MobSpawner {
		if($delay < $this->maxSpawnDelay and $delay > $this->minSpawnDelay) {
			$this->delay = $delay;
		}
		return $this;
	}

	/**
	 * @param int $range
	 *
	 * @return MobSpawner
	 */
	public function setRequiredPlayerRange(int $range): MobSpawner {
		if($range < 0) {
			$range = 0;
		}
		$this->requiredPlayerRange = $range;
		return $this;
	}

	/**
	 * @param int $count
	 *
	 * @return MobSpawner
	 */
	public function setMaxNearbyEntities(int $count): MobSpawner {
		$this->maxNearbyEntities = $count;
		return $this;
	}

	/**
	 * @param bool $isMovable
	 *
	 * @return MobSpawner
	 */
	public function setMovable(bool $isMovable = true): MobSpawner {
		$this->isMovable = $isMovable;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isMovable(): bool {
		return $this->isMovable;
	}
}