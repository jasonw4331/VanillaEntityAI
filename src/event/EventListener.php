<?php

declare(strict_types=1);

namespace jasonw4331\VanillaEntityAI\event;

use jasonw4331\VanillaEntityAI\data\NaturalSpawnTaskCollector;
use jasonw4331\VanillaEntityAI\data\SpawnerTaskCollector;
use jasonw4331\VanillaEntityAI\Main;
use jasonw4331\VanillaEntityAI\task\NaturalAnimalSpawnTask;
use jasonw4331\VanillaEntityAI\task\NaturalMonsterSpawnTask;
use jasonw4331\VanillaEntityAI\task\SpawnerTask;
use jasonw4331\VanillaEntityAI\util\MonsterSpawnerConstants;
use pocketmine\block\CarvedPumpkin;
use pocketmine\block\MonsterSpawner;
use pocketmine\block\Snow;
use pocketmine\block\tile\MonsterSpawner as MonsterSpawnerTile;
use pocketmine\block\VanillaBlocks;
use pocketmine\data\bedrock\LegacyEntityIdToStringIdMap;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\event\world\ChunkLoadEvent;
use pocketmine\event\world\WorldLoadEvent;
use pocketmine\event\world\WorldSaveEvent;
use pocketmine\item\SpawnEgg;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\BlockActorDataPacket;
use pocketmine\network\mcpe\protocol\SetDifficultyPacket;
use pocketmine\player\GameMode;
use pocketmine\scheduler\ClosureTask;
use pocketmine\world\World;

final class EventListener implements Listener{

	public function __construct(private Main $plugin){
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	/**
	 * @priority MONITOR
	 */
	public function onChunkLoad(ChunkLoadEvent $event) : void {
		$chunk = $event->getChunk();
		$chunkTiles = $chunk->getTiles();
		// use reflection to acquire all tile private properties data
		$reflection = new \ReflectionClass(MonsterSpawnerTile::class);
		$properties = $reflection->getProperties(\ReflectionProperty::IS_PRIVATE);
		foreach($properties as $property){
			$property->setAccessible(true);
		}
		foreach($chunkTiles as $tile) {
			if($tile instanceof MonsterSpawnerTile) {
				$tileData = [];
				foreach($properties as $property) {
					$tileData[$property->getName()] = $property->getValue($tile);
				}
				// use tile data to create task handling equivalent mob spawning logic
				$task = new SpawnerTask($this->plugin, $tile->getPosition(), $tileData); // we can trust save data to be valid
				$task->onRun();
				SpawnerTaskCollector::getInstance()->add($tile->getPosition(), $task);
			}
		}
	}

	/**
	 * @priority MONITOR
	 */
	public function onWorldSave(WorldSaveEvent $event) : void {
		$chunks = $event->getWorld()->getLoadedChunks();
		foreach($chunks as $chunk) {
			$chunkTiles = $chunk->getTiles();
			foreach($chunkTiles as $tile) {
				if($tile instanceof MonsterSpawnerTile) {
					$task = SpawnerTaskCollector::getInstance()->getAt($tile->getPosition());
					if($task !== null) {
						// use reflection to insert task data back into tile
						$reflection = new \ReflectionClass($task);
						$properties = $reflection->getProperties(\ReflectionProperty::IS_PRIVATE);
						foreach($properties as $property){
							$property->setAccessible(true);
							$property->setValue($tile, $task->getTileData()[$property->getName()]);
						}
					}
				}
			}
		}
	}

	/**
	 * @priority MONITOR
	 * @noinspection PhpMissingBreakStatementInspection
	 */
	public function onWorldLoad(WorldLoadEvent $event) : void {
		// start Natural Spawn tasks based on world settings
		$world = $event->getWorld();
		$difficulty = $world->getDifficulty();
		$disabledWorlds = $this->plugin->getConfig()->get("Disabled Worlds", []);
		if(in_array($world->getFolderName(), $disabledWorlds, true)) {
			return;
		}
		$animalsEnabled = $this->plugin->getConfig()->get("Animals Enabled", true);
		$monstersEnabled = $this->plugin->getConfig()->get("Monsters Enabled", true);
		switch($difficulty) {
			case World::DIFFICULTY_EASY:
			case World::DIFFICULTY_NORMAL:
			case World::DIFFICULTY_HARD:
				if($monstersEnabled) {
					// start natural monster spawn task
					// then register task to task collector
					$task = new NaturalMonsterSpawnTask($this->plugin, $world);
					$this->plugin->getScheduler()->scheduleRepeatingTask($task, 1);
				}
			case World::DIFFICULTY_PEACEFUL:
				if($animalsEnabled) {
					// start natural animal spawn task
					// then register task to task collector
					$task = new NaturalAnimalSpawnTask($this->plugin, $world);
					$this->plugin->getScheduler()->scheduleRepeatingTask($task, 20);
				}
		}
	}

	/**
	 * @priority MONITOR
	 */
	public function onBlockPlace(BlockPlaceEvent $event) : void{
		$block = $event->getBlock();
		$pos = $block->getPosition();

		if($block instanceof MonsterSpawner) {
			/** @var MonsterSpawnerTile $tile */
			$tile = $pos->getWorld()->getTile($pos);

			// use reflection to acquire all tile private properties data
			$reflection = new \ReflectionClass($tile);
			$properties = $reflection->getProperties(\ReflectionProperty::IS_PRIVATE);
			$tileData = [];
			foreach($properties as $property){
				$property->setAccessible(true);
				$tileData[$property->getName()] = $property->getValue($tile);
			}

			// use tile data to create task handling equivalent mob spawning logic
			$task = new SpawnerTask($this->plugin, $pos, $tileData);
			$task->onRun();
			SpawnerTaskCollector::getInstance()->add($pos, $task);
			return;
		}
		if($block instanceof CarvedPumpkin || $block instanceof Snow) {
			// detect snowman block pattern from surrounding blocks
			// order -z, +z, -x, +x, +y, -y
			$block1 = null;
			$block2 = null;
			$facing = null;
			foreach(Facing::HORIZONTAL as $face) {
				$block1 = $block->getSide($face);
				$block2 = $block->getSide($face, 2);
				if($block instanceof CarvedPumpkin && $block1 instanceof Snow && $block2 instanceof Snow) {
					$facing = $face;
					break;
				}
				$block1 = $block->getSide(Facing::opposite($face));
				$block2 = $block->getSide($face);
				if($block instanceof Snow && $block1 instanceof CarvedPumpkin && $block2 instanceof Snow) {
					$facing = $face;
					break;
				}
				$block1 = $block->getSide(Facing::opposite($face));
				$block2 = $block->getSide(Facing::opposite($face), 2);
				if($block instanceof Snow && $block1 instanceof Snow && $block2 instanceof CarvedPumpkin) {
					$facing = $face;
					break;
				}
			}
			if($facing === null) {
				$block1 = $block->getSide(Facing::UP);
				$block2 = $block->getSide(Facing::UP, 2);
				if($block instanceof CarvedPumpkin && $block1 instanceof Snow && $block2 instanceof Snow) {
					$facing = Facing::UP;
				}else{
					$block1 = $block->getSide(Facing::opposite(Facing::UP));
					$block2 = $block->getSide(Facing::UP);
					if($block instanceof Snow && $block1 instanceof CarvedPumpkin && $block2 instanceof Snow) {
						$facing = Facing::UP;
					}else{
						$block1 = $block->getSide(Facing::opposite(Facing::UP));
						$block2 = $block->getSide(Facing::opposite(Facing::UP), 2);
						if($block instanceof Snow && $block1 instanceof Snow && $block2 instanceof CarvedPumpkin) {
							$facing = Facing::UP;
						}
					}
				}
			}
			if($facing === null) {
				$block1 = $block->getSide(Facing::DOWN);
				$block2 = $block->getSide(Facing::DOWN, 2);
				if($block instanceof CarvedPumpkin && $block1 instanceof Snow && $block2 instanceof Snow) {
					$facing = Facing::DOWN;
				}else{
					$block1 = $block->getSide(Facing::opposite(Facing::DOWN));
					$block2 = $block->getSide(Facing::DOWN);
					if($block instanceof Snow && $block1 instanceof CarvedPumpkin && $block2 instanceof Snow) {
						$facing = Facing::DOWN;
					}else{
						$block1 = $block->getSide(Facing::opposite(Facing::DOWN));
						$block2 = $block->getSide(Facing::opposite(Facing::DOWN), 2);
						if($block instanceof Snow && $block1 instanceof Snow && $block2 instanceof CarvedPumpkin) {
							$facing = Facing::DOWN;
						}
					}
				}
			}
			if($facing !== null) {
				$this->plugin->getScheduler()->scheduleTask(new ClosureTask(static function() use($block, $block1, $block2) {
					$pos = $block->getPosition();
					$pos1 = $block1->getPosition();
					$pos2 = $block2->getPosition();
					$world = $pos->getWorld();
					$world->setBlock($pos, VanillaBlocks::AIR());
					$world->setBlock($pos1, VanillaBlocks::AIR());
					$world->setBlock($pos2, VanillaBlocks::AIR());

					// TODO: uncomment when snowman is implemented
					/*$snowman = new Snowman(new Location(
						min($pos->x, $pos1->x, $pos2->x) + 0.5,
						min($pos->y, $pos1->y, $pos2->y),
						min($pos->z, $pos1->z, $pos2->z) + 0.5,
						$world,
						0.0,
						0.0
					));
					$snowman->spawnToAll();*/
				}));
				return;
			}
		}
		if($block instanceof CarvedPumpkin || $block->getIdInfo() === VanillaBlocks::IRON()->getIdInfo()) {
			$ironId = VanillaBlocks::IRON()->getIdInfo();
			$airblockId = VanillaBlocks::AIR()->getIdInfo();
			// detect irongolem blocks from anywhere within pattern space relative to block along horizontal axis
			// order -z, +z, -x, +x, +y, -y
			$block1 = null;
			$block2 = null;
			$block3 = null;
			$block4 = null;
			$facing = null;
			foreach(Facing::HORIZONTAL as $face) {
				$block1 = $block->getSide($face);
				$block2 = $block->getSide($face, 2);
				$block3 = $block1->getSide(Facing::rotateY($face, true));
				$block4 = $block1->getSide(Facing::rotateY($face, false));
				$airBlock1 = $block3->getSide($face);
				$airBlock2 = $block3->getSide(Facing::opposite($face));
				$airBlock3 = $block4->getSide($face);
				$airBlock4 = $block4->getSide(Facing::opposite($face));
				if($block instanceof CarvedPumpkin &&
					$block1->getIdInfo() === $ironId &&
					$block2->getIdInfo() === $ironId &&
					$block3->getIdInfo() === $ironId &&
					$block4->getIdInfo() === $ironId &&
					$airBlock1->getIdInfo() === $airblockId &&
					$airBlock2->getIdInfo() === $airblockId &&
					$airBlock3->getIdInfo() === $airblockId &&
					$airBlock4->getIdInfo() === $airblockId
				) {
					$facing = $face;
					break;
				}
				$block1 = $block->getSide(Facing::opposite($face));
				$block2 = $block->getSide($face);
				$block3 = $block->getSide(Facing::rotateY($face, true));
				$block4 = $block->getSide(Facing::rotateY($face, false));
				$airBlock1 = $block3->getSide($face);
				$airBlock2 = $block3->getSide(Facing::opposite($face));
				$airBlock3 = $block4->getSide($face);
				$airBlock4 = $block4->getSide(Facing::opposite($face));
				if($block->getIdInfo() === $ironId &&
					$block1 instanceof CarvedPumpkin &&
					$block2->getIdInfo() === $ironId &&
					$block3->getIdInfo() === $ironId &&
					$block4->getIdInfo() === $ironId &&
					$airBlock1->getIdInfo() === $airblockId &&
					$airBlock2->getIdInfo() === $airblockId &&
					$airBlock3->getIdInfo() === $airblockId &&
					$airBlock4->getIdInfo() === $airblockId
				) {
					$facing = $face;
					break;
				}
				$block1 = $block->getSide(Facing::opposite($face));
				$block2 = $block->getSide(Facing::opposite($face), 2);
				$block3 = $block1->getSide(Facing::rotateY($face, true));
				$block4 = $block1->getSide(Facing::rotateY($face, false));
				$airBlock1 = $block3->getSide($face);
				$airBlock2 = $block3->getSide(Facing::opposite($face));
				$airBlock3 = $block4->getSide($face);
				$airBlock4 = $block4->getSide(Facing::opposite($face));
				if($block->getIdInfo() === $ironId &&
					$block1->getIdInfo() === $ironId &&
					$block2 instanceof CarvedPumpkin &&
					$block3->getIdInfo() === $ironId &&
					$block4->getIdInfo() === $ironId &&
					$airBlock1->getIdInfo() === $airblockId &&
					$airBlock2->getIdInfo() === $airblockId &&
					$airBlock3->getIdInfo() === $airblockId &&
					$airBlock4->getIdInfo() === $airblockId
				) {
					$facing = $face;
					break;
				}
			}
			if($facing === null) {
				$block1 = $block->getSide(Facing::UP);
				$block2 = $block->getSide(Facing::UP, 2);
				$block3 = $block1->getSide(Facing::rotateX(Facing::UP, true));
				$block4 = $block1->getSide(Facing::rotateX(Facing::UP, false));
				if($block3->getIdInfo() !== $ironId || $block4->getIdInfo() !== $ironId) {
					$block3 = $block1->getSide(Facing::rotateZ(Facing::UP, true));
					$block4 = $block1->getSide(Facing::rotateZ(Facing::UP, false));
				}
				$airBlock1 = $block3->getSide(Facing::UP);
				$airBlock2 = $block3->getSide(Facing::opposite(Facing::UP));
				$airBlock3 = $block4->getSide(Facing::UP);
				$airBlock4 = $block4->getSide(Facing::opposite(Facing::UP));
				if($block instanceof CarvedPumpkin &&
					$block1->getIdInfo() === $ironId &&
					$block2->getIdInfo() === $ironId &&
					$block3->getIdInfo() === $ironId &&
					$block4->getIdInfo() === $ironId &&
					$airBlock1->getIdInfo() === $airblockId &&
					$airBlock2->getIdInfo() === $airblockId &&
					$airBlock3->getIdInfo() === $airblockId &&
					$airBlock4->getIdInfo() === $airblockId
				) {
					$facing = Facing::UP;
				}else{
					$block1 = $block->getSide(Facing::opposite(Facing::UP));
					$block2 = $block->getSide(Facing::UP);
					$block3 = $block->getSide(Facing::rotateX(Facing::UP, true));
					$block4 = $block->getSide(Facing::rotateX(Facing::UP, false));
					if($block3->getIdInfo() !== $ironId || $block4->getIdInfo() !== $ironId) {
						$block3 = $block->getSide(Facing::rotateZ(Facing::UP, true));
						$block4 = $block->getSide(Facing::rotateZ(Facing::UP, false));
					}
					$airBlock1 = $block3->getSide(Facing::UP);
					$airBlock2 = $block3->getSide(Facing::opposite(Facing::UP));
					$airBlock3 = $block4->getSide(Facing::UP);
					$airBlock4 = $block4->getSide(Facing::opposite(Facing::UP));
					if($block->getIdInfo() === $ironId &&
						$block1 instanceof CarvedPumpkin &&
						$block2->getIdInfo() === $ironId &&
						$block3->getIdInfo() === $ironId &&
						$block4->getIdInfo() === $ironId &&
						$airBlock1->getIdInfo() === $airblockId &&
						$airBlock2->getIdInfo() === $airblockId &&
						$airBlock3->getIdInfo() === $airblockId &&
						$airBlock4->getIdInfo() === $airblockId
					) {
						$facing = Facing::UP;
					}else{
						$block1 = $block->getSide(Facing::opposite(Facing::UP));
						$block2 = $block->getSide(Facing::opposite(Facing::UP), 2);
						$block3 = $block1->getSide(Facing::rotateX(Facing::UP, true));
						$block4 = $block1->getSide(Facing::rotateX(Facing::UP, false));
						if($block3->getIdInfo() !== $ironId || $block4->getIdInfo() !== $ironId) {
							$block3 = $block2->getSide(Facing::rotateZ(Facing::UP, true));
							$block4 = $block2->getSide(Facing::rotateZ(Facing::UP, false));
						}
						$airBlock1 = $block3->getSide(Facing::UP);
						$airBlock2 = $block3->getSide(Facing::opposite(Facing::UP));
						$airBlock3 = $block4->getSide(Facing::UP);
						$airBlock4 = $block4->getSide(Facing::opposite(Facing::UP));
						if($block->getIdInfo() === $ironId &&
							$block1->getIdInfo() === $ironId &&
							$block2 instanceof CarvedPumpkin &&
							$block3->getIdInfo() === $ironId &&
							$block4->getIdInfo() === $ironId &&
							$airBlock1->getIdInfo() === $airblockId &&
							$airBlock2->getIdInfo() === $airblockId &&
							$airBlock3->getIdInfo() === $airblockId &&
							$airBlock4->getIdInfo() === $airblockId
						) {
							$facing = Facing::UP;
						}
					}
				}
			}
			if($facing === null) {
				$block1 = $block->getSide(Facing::DOWN);
				$block2 = $block->getSide(Facing::DOWN, 2);
				$block3 = $block1->getSide(Facing::rotateX(Facing::DOWN, true));
				$block4 = $block1->getSide(Facing::rotateX(Facing::DOWN, false));
				if($block3->getIdInfo() !== $ironId || $block4->getIdInfo() !== $ironId) {
					$block3 = $block1->getSide(Facing::rotateZ(Facing::DOWN, true));
					$block4 = $block1->getSide(Facing::rotateZ(Facing::DOWN, false));
				}
				$airBlock1 = $block3->getSide(Facing::DOWN);
				$airBlock2 = $block3->getSide(Facing::opposite(Facing::DOWN));
				$airBlock3 = $block4->getSide(Facing::DOWN);
				$airBlock4 = $block4->getSide(Facing::opposite(Facing::DOWN));
				if($block instanceof CarvedPumpkin &&
					$block1->getIdInfo() === $ironId &&
					$block2->getIdInfo() === $ironId &&
					$block3->getIdInfo() === $ironId &&
					$block4->getIdInfo() === $ironId &&
					$airBlock1->getIdInfo() === $airblockId &&
					$airBlock2->getIdInfo() === $airblockId &&
					$airBlock3->getIdInfo() === $airblockId &&
					$airBlock4->getIdInfo() === $airblockId
				) {
					$facing = Facing::DOWN;
				}else{
					$block1 = $block->getSide(Facing::opposite(Facing::DOWN));
					$block2 = $block->getSide(Facing::DOWN);
					$block3 = $block->getSide(Facing::rotateX(Facing::DOWN, true));
					$block4 = $block->getSide(Facing::rotateX(Facing::DOWN, false));
					if($block3->getIdInfo() !== $ironId || $block4->getIdInfo() !== $ironId) {
						$block3 = $block->getSide(Facing::rotateZ(Facing::DOWN, true));
						$block4 = $block->getSide(Facing::rotateZ(Facing::DOWN, false));
					}
					$airBlock1 = $block3->getSide(Facing::DOWN);
					$airBlock2 = $block3->getSide(Facing::opposite(Facing::DOWN));
					$airBlock3 = $block4->getSide(Facing::DOWN);
					$airBlock4 = $block4->getSide(Facing::opposite(Facing::DOWN));
					if($block->getIdInfo() === $ironId &&
						$block1 instanceof CarvedPumpkin &&
						$block2->getIdInfo() === $ironId &&
						$block3->getIdInfo() === $ironId &&
						$block4->getIdInfo() === $ironId &&
						$airBlock1->getIdInfo() === $airblockId &&
						$airBlock2->getIdInfo() === $airblockId &&
						$airBlock3->getIdInfo() === $airblockId &&
						$airBlock4->getIdInfo() === $airblockId
					) {
						$facing = Facing::DOWN;
					}else{
						$block1 = $block->getSide(Facing::opposite(Facing::DOWN));
						$block2 = $block->getSide(Facing::opposite(Facing::DOWN), 2);
						$block3 = $block1->getSide(Facing::rotateX(Facing::DOWN, true));
						$block4 = $block1->getSide(Facing::rotateX(Facing::DOWN, false));
						if($block3->getIdInfo() !== $ironId || $block4->getIdInfo() !== $ironId) {
							$block3 = $block2->getSide(Facing::rotateZ(Facing::DOWN, true));
							$block4 = $block2->getSide(Facing::rotateZ(Facing::DOWN, false));
						}
						$airBlock1 = $block3->getSide(Facing::DOWN);
						$airBlock2 = $block3->getSide(Facing::opposite(Facing::DOWN));
						$airBlock3 = $block4->getSide(Facing::DOWN);
						$airBlock4 = $block4->getSide(Facing::opposite(Facing::DOWN));
						if($block->getIdInfo() === $ironId &&
							$block1->getIdInfo() === $ironId &&
							$block2 instanceof CarvedPumpkin &&
							$block3->getIdInfo() === $ironId &&
							$block4->getIdInfo() === $ironId &&
							$airBlock1->getIdInfo() === $airblockId &&
							$airBlock2->getIdInfo() === $airblockId &&
							$airBlock3->getIdInfo() === $airblockId &&
							$airBlock4->getIdInfo() === $airblockId
						) {
							$facing = Facing::DOWN;
						}
					}
				}
			}
			if($facing !== null) {
				$this->plugin->getScheduler()->scheduleTask(new ClosureTask(static function() use($block, $block1, $block2, $block3, $block4) {
					$pos = $block->getPosition();
					$pos1 = $block1->getPosition();
					$pos2 = $block2->getPosition();
					$pos3 = $block3->getPosition();
					$pos4 = $block4->getPosition();
					$world = $pos->getWorld();
					$world->setBlock($pos, VanillaBlocks::AIR());
					$world->setBlock($pos1, VanillaBlocks::AIR());
					$world->setBlock($pos2, VanillaBlocks::AIR());
					$world->setBlock($pos3, VanillaBlocks::AIR());
					$world->setBlock($pos4, VanillaBlocks::AIR());

					// TODO: uncomment when IronGolem is implemented
					/*$ironGolem = new IronGolem(new Location(
						min($pos->x, $pos1->x, $pos2->x) + 0.5,
						min($pos->y, $pos1->y, $pos2->y),
						min($pos->z, $pos1->z, $pos2->z) + 0.5,
						$world,
						0.0,
						0.0
					));
					$ironGolem->spawnToAll();*/
				}));
				return;
			}
		}
	}

	/**
	 * @priority HIGHEST
	 */
	public function onItemUse(PlayerInteractEvent $event) : void{
		$player = $event->getPlayer();
		$item = $event->getItem();
		$block = $event->getBlock();

		if($block instanceof MonsterSpawner && $item instanceof SpawnEgg) {
			if($player->getGamemode()->equals(GameMode::SURVIVAL())) {
				$item->pop();
			}elseif($player->getGamemode()->equals(GameMode::ADVENTURE())) {
				$event->cancel();
				return;
			}
			// update task information to reflect block data changes
			$task = SpawnerTaskCollector::getInstance()->getAt($block->getPosition());
			if($task === null)
				return;
			$entityId = LegacyEntityIdToStringIdMap::getInstance()->legacyToString($item->getMeta()) ?? ':';
			$task->setEntityId($entityId);
			if($task->getHandler() === null)
				$task->onRun(); // run task if it's not scheduled already
			$event->cancel(); // prevent spawn egg from spawning a mob
		}
	}

	/**
	 * @priority MONITOR
	 */
	public function onPacketSend(DataPacketSendEvent $event) : void {
		$packets = $event->getPackets();
		$world = $event->getTargets()[0]->getPlayer()?->getWorld();
		if($world === null)
			return;
		foreach($packets as $packet) {
			if($packet instanceof BlockActorDataPacket) {
				$coords = $packet->blockPosition;
				/** @var MonsterSpawnerTile $tile */
				$tile = $world->getTile(new Vector3($coords->getX(), $coords->getY(), $coords->getZ()));
				if($tile === null)
					continue;

				$task = SpawnerTaskCollector::getInstance()->getAt($tile->getPosition());
				if($task === null)
					continue;

				$nbtCache = $tile->getSerializedSpawnCompound();
				$rootTag = $nbtCache->getRoot();
				// entity identifier already set in tag
				$rootTag->setShort(MonsterSpawnerConstants::TAG_SPAWN_DELAY, $task->getSpawnDelay())
					->setShort(MonsterSpawnerConstants::TAG_MIN_SPAWN_DELAY, $task->getMinSpawnDelay())
					->setShort(MonsterSpawnerConstants::TAG_MAX_SPAWN_DELAY, $task->getMaxSpawnDelay())
					->setShort(MonsterSpawnerConstants::TAG_SPAWN_PER_ATTEMPT, $task->getSpawnCount())
					->setShort(MonsterSpawnerConstants::TAG_MAX_NEARBY_ENTITIES, $task->getMaxNearbyEntities())
					->setShort(MonsterSpawnerConstants::TAG_REQUIRED_PLAYER_RANGE, $task->getRequiredPlayerRange())
					->setShort(MonsterSpawnerConstants::TAG_SPAWN_RANGE, $task->getSpawnRange())
					->setFloat(MonsterSpawnerConstants::TAG_ENTITY_WIDTH, $task->getEntityWidth())
					->setFloat(MonsterSpawnerConstants::TAG_ENTITY_HEIGHT, $task->getEntityHeight());
					//->setFloat(MonsterSpawnerConstants::TAG_ENTITY_SCALE,) // scale is already set in tag
					/*->setTag(MonsterSpawnerConstants::TAG_SPAWN_DATA, // might not exist
						CompoundTag::create()
						->setTag(MonsterSpawnerConstants::TAG_SUB_PROPERTIES,
							CompoundTag::create() // unknown contents
						)
						->setString(MonsterSpawnerConstants::TAG_SUB_TYPE_ID, '') // namespaced entity identifier
						->setInt(MonsterSpawnerConstants::TAG_SUB_WEIGHT, 1) // unknown data validation
					)
					->setTag(MonsterSpawnerConstants::TAG_SPAWN_POTENTIALS, new ListTag([ // might not exist
						CompoundTag::create()
							->setTag(MonsterSpawnerConstants::TAG_SUB_PROPERTIES,
								CompoundTag::create() // unknown contents
							)
							->setString(MonsterSpawnerConstants::TAG_SUB_TYPE_ID, '') // namespaced entity identifier
							->setInt(MonsterSpawnerConstants::TAG_SUB_WEIGHT, 1) // Must be positive and at least 1.
					], NBT::TAG_Compound));*/

				$packet->nbt = $nbtCache;
			}
			if($packet instanceof SetDifficultyPacket) {
				NaturalSpawnTaskCollector::getInstance()->updateWorldSettings($world);
			}
		}
	}
}
