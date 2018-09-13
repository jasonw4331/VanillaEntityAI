<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\tile;

use jasonwynn10\VanillaEntityAI\entity\CreatureBase;
use jasonwynn10\VanillaEntityAI\EntityAI;
use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\AxisAlignedBB;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\tile\Spawnable;

class MobSpawner extends Spawnable {
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
	/** @var CompoundTag|null $spawnData */
	protected $spawnData;
	/** @var ListTag|null $spawnPotentials */
	protected $spawnPotentials;
	/** @var AxisAlignedBB|null $spawnArea */
	protected $spawnArea;
	/** @var bool $keepPacked */
	private $keepPacked = false;

	/**
	 * @return bool
	 */
	public function onUpdate(): bool {
		if($this->isClosed()) {
			return false;
		}
		if(isset($this->spawnData)) {
			$entityTag = clone $this->spawnData;
			$entityTag->setName("Entity");
			$this->spawnPotentials = new ListTag("SpawnPotentials", [new CompoundTag("", [$entityTag, new IntTag("Weight", 1)])], NBT::TAG_Compound);
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
							$entity = $class::spawnMob($this->getRandomSpawnPos(), $this->spawnData);
							if($entity !== null) {
								$entity->spawnToAll();
								$spawned++;
							}
						}
					}
				}else {
					var_dump($class); // TODO: remove
				}
			}
			/** @var CompoundTag $blankTag */
			foreach($this->spawnPotentials->getAllValues() as $blankTag) {
				if($blankTag->hasTag("Entity", CompoundTag::class) and $blankTag->hasTag("Weight", IntTag::class) and $weight = $blankTag->getInt("Weight") > 0) {
					// TODO: weighted randomness
				}
			}
		}elseif($this->delay === -1) {
			$this->delay = mt_rand($this->minSpawnDelay, $this->maxSpawnDelay);
			$this->spawnData = new CompoundTag("SpawnData", [new IntTag("id", Entity::PIG)]); // TODO: randomize
			$this->spawnPotentials = new ListTag("SpawnPotentials", [], NBT::TAG_Compound); // TODO: randomize
		}
		$this->scheduleUpdate();
		return true;
	}

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
		return new Position($x, $y, $z, $this->level);
	}

	/**
	 * Reads additional data from the CompoundTag on tile creation.
	 *
	 * @param CompoundTag $nbt
	 */
	protected function readSaveData(CompoundTag $nbt): void {
		if($nbt->hasTag("SpawnData", CompoundTag::class)) {
			$this->spawnData = $nbt->getCompoundTag("SpawnData");
		}
		if($nbt->hasTag("SpawnPotentials", ListTag::class)) {
			$this->spawnPotentials = $nbt->getListTag("SpawnPotentials");
		}elseif($this->spawnData !== null) {
			$this->spawnPotentials = new ListTag("SpawnPotentials", [], NBT::TAG_Compound);
		}
		if($nbt->hasTag("SpawnCount", ShortTag::class)) {
			$this->spawnCount = $nbt->getShort("SpawnCount");
		}
		if($nbt->hasTag("SpawnRange", ShortTag::class)) {
			$this->spawnRange = $nbt->getShort("SpawnRange");
		}
		$this->spawnArea = new AxisAlignedBB($this->x - $this->spawnRange, $this->y - 1, $this->z - $this->spawnRange, $this->x + $this->spawnRange, $this->y + 1, $this->z + $this->spawnRange);
		if($nbt->hasTag("Delay", ShortTag::class)) {
			$this->delay = $nbt->getShort("Delay");
		}
		if($nbt->hasTag("MinSpawnDelay", ShortTag::class)) {
			$this->minSpawnDelay = $nbt->getShort("MinSpawnDelay");
		}
		if($nbt->hasTag("MaxSpawnDelay", ShortTag::class)) {
			$this->maxSpawnDelay = $nbt->getShort("MaxSpawnDelay");
		}
		if($nbt->hasTag("MaxNearbyEntities", ShortTag::class)) {
			$this->maxNearbyEntities = $nbt->getShort("MaxNearbyEntities");
		}
		if($nbt->hasTag("RequiredPlayerRange", ShortTag::class)) {
			$this->requiredPlayerRange = $nbt->getShort("RequiredPlayerRange");
		}
		if($nbt->hasTag("keepPacked", ByteTag::class)) {
			$this->keepPacked = $nbt->getByte("keepPacked");
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
		if(isset($this->spawnData)) {
			$nbt->setTag($this->spawnData); // Contains tags to copy to the next spawned entity(s) after spawning. Any of the entity or mob tags may be used. Note that if a spawner specifies any of these tags, almost all variable data such as mob equipment, villager profession, sheep wool color, etc., will not be automatically generated, and must also be manually specified (note that this does not apply to position data, which will be randomized as normal unless Pos is specified. Similarly, unless Size and Health are specified for a Slime or Magma Cube, these will still be randomized). This, together with EntityId, also determines the appearance of the miniature entity spinning in the spawner cage. Note: this tag is optional: if it does not exist, the next spawned entity will use the default vanilla spawning properties for this mob, including potentially randomized armor (this is true even if SpawnPotentials does exist). Warning: If SpawnPotentials exists, this tag will get overwritten after the next spawning attempt: see above for more details.
		}
		if(isset($this->spawnPotentials)) {
			$nbt->setTag($this->spawnPotentials); // Optional. List of possible entities to spawn. If this tag does not exist, but SpawnData exists, Minecraft will generate it the next time the spawner tries to spawn an entity. The generated list will contain a single entry derived from the EntityId and SpawnData tags.
		}
		$nbt->setShort("SpawnCount", $this->spawnCount); // How many mobs to attempt to spawn each time. Requires MinSpawnDelay to be set
		$nbt->setShort("SpawnRange", $this->spawnRange); // The radius around which the spawner attempts to place mobs randomly. The spawn area is square, includes the block the spawner is in, and is centered around the spawner's x,z coordinates - not the spawner itself. It is 2 blocks high, centered around the spawner's y coordinate (its bottom), allowing mobs to spawn as high as its top surface and as low as 1 block below its bottom surface. Vertical spawn coordinates are integers, while horizontal coordinates are floating point and weighted towards values near the spawner itself. Default value is 4.
		$nbt->setShort("Delay", $this->delay); // Ticks until next spawn. If 0, it will spawn immediately when a player enters its range. If set to -1 (this state never occurs in a natural spawner; it seems to be a feature accessed only via NBT editing), the spawner will reset its Delay, and (if SpawnPotentials exist) EntityID and SpawnData as though it had just completed a successful spawn cycle, immediately when a player enters its range. Note that setting Delay to -1 can be useful if you want the game to properly randomize the spawner's Delay, EntityID, and SpawnData, rather than starting with pre-defined values.
		$nbt->setShort("MinSpawnDelay", $this->minSpawnDelay); // The minimum random delay for the next spawn delay. May be equal to MaxSpawnDelay.
		$nbt->setShort("MaxSpawnDelay", $this->maxSpawnDelay); // The maximum random delay for the next spawn delay. Warning: Setting this value to 0 crashes Minecraft. Set to at least 1. Note: Requires the MinSpawnDelay property to also be set.
		$nbt->setShort("MaxNearbyEntities", $this->maxNearbyEntities); //Overrides the maximum number of nearby (within a box of spawnrange*2+1 x spawnrange*2+1 x 8 centered around the spawner block) entities whose IDs match this spawner's entity ID. Note that this is relative to a mob's hitbox, not their physical position. Also note that all entities within all chunk sections (16x16x16 cubes) overlapped by this box are tested for their ID and hitbox overlap, rather than just entities which are within the box, meaning a large amount of entities outside the box (or within it, of course) can cause substantial lag.
		$nbt->setShort("RequiredPlayerRange", $this->requiredPlayerRange); // Overrides the block radius of the sphere of activation by players for this spawner. Note that for every gametick, a spawner will check all players in the current world to test whether a player is within this sphere. Note: Requires the MaxNearbyEntities property to also be set.
		$nbt->setByte("keepPacked", (int)$this->keepPacked); // There is ZERO information on this tag anywhere
	}

	/**
	 * @return int
	 */
	public function getEntityId(): int {
		return isset($this->spawnData) ? $this->spawnData->getInt("id", -1) : -1;
	}

	/**
	 * @param int $id
	 *
	 * @return MobSpawner
	 */
	public function setEntityId(int $id): MobSpawner {
		$this->spawnData = new CompoundTag("SpawnData", [new IntTag("id", $id)]);
		$this->onChanged();
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
		if($this->minSpawnDelay < $maxDelay and $maxDelay !== 0)
			$this->maxSpawnDelay = $maxDelay;
		return $this;
	}

	/**
	 * @param int $delay
	 *
	 * @return MobSpawner
	 */
	public function setSpawnDelay(int $delay) : MobSpawner {
		if($delay < $this->maxSpawnDelay)
			$this->delay = $delay;
		return $this;
	}

	/**
	 * @param int $range
	 *
	 * @return MobSpawner
	 */
	public function setRequiredPlayerRange(int $range): MobSpawner {
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
	 * @return CompoundTag|array|null
	 */
	public function getSpawnData() : CompoundTag {
		return $this->spawnData ?? new CompoundTag("SpawnData");
	}

	/**
	 * @param CompoundTag $spawnData
	 *
	 * @return MobSpawner
	 */
	public function setSpawnData(CompoundTag $spawnData) : MobSpawner {
		$this->spawnData = $spawnData;
		return $this;
	}

	/**
	 * @return ListTag|array|null
	 */
	public function getSpawnPotentials() : ListTag {
		return $this->spawnPotentials;
	}

	/**
	 * @param ListTag $spawnPotentials
	 *
	 * @return MobSpawner
	 */
	public function setSpawnPotentials(ListTag $spawnPotentials) : MobSpawner {
		$this->spawnPotentials = $spawnPotentials;
		// TODO: check if valid
		return $this;
	}
}