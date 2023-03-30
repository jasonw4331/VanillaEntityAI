<?php
declare(strict_types=1);

namespace jasonwynn10\VanillaEntityAI\util;

interface MonsterSpawnerConstants{
	const TAG_ENTITY_TYPE_ID = "EntityIdentifier"; //TAG_String
	const TAG_SPAWN_DELAY = "Delay"; //TAG_Short
	const TAG_SPAWN_POTENTIALS = "SpawnPotentials"; //TAG_List<TAG_Compound>
	const TAG_SUB_TYPE_ID = "TypeId"; //TAG_String
	const TAG_SUB_PROPERTIES = "Properties"; //TAG_Compound
	const TAG_SUB_WEIGHT = "Weight"; //TAG_Int
	const TAG_SPAWN_DATA = "SpawnData"; //TAG_Compound
	const TAG_MIN_SPAWN_DELAY = "MinSpawnDelay"; //TAG_Short
	const TAG_MAX_SPAWN_DELAY = "MaxSpawnDelay"; //TAG_Short
	const TAG_SPAWN_PER_ATTEMPT = "SpawnCount"; //TAG_Short
	const TAG_MAX_NEARBY_ENTITIES = "MaxNearbyEntities"; //TAG_Short
	const TAG_REQUIRED_PLAYER_RANGE = "RequiredPlayerRange"; //TAG_Short
	const TAG_SPAWN_RANGE = "SpawnRange"; //TAG_Short
	const TAG_ENTITY_WIDTH = "DisplayEntityWidth"; //TAG_Float
	const TAG_ENTITY_HEIGHT = "DisplayEntityHeight"; //TAG_Float
	const TAG_ENTITY_SCALE = "DisplayEntityScale"; //TAG_Float
}