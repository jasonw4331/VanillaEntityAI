<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\data;

use jasonwynn10\VanillaEntityAI\entity\hostile\Creeper;
use jasonwynn10\VanillaEntityAI\entity\hostile\Ghast;
use jasonwynn10\VanillaEntityAI\entity\hostile\Husk;
use jasonwynn10\VanillaEntityAI\entity\hostile\Skeleton;
use jasonwynn10\VanillaEntityAI\entity\hostile\Spider;
use jasonwynn10\VanillaEntityAI\entity\hostile\Stray;
use jasonwynn10\VanillaEntityAI\entity\hostile\Zombie;
use jasonwynn10\VanillaEntityAI\entity\hostile\ZombiePigman;
use jasonwynn10\VanillaEntityAI\entity\passive\Squid;
use pocketmine\level\biome\Biome;

class BiomeEntityList {
	/** @var int[][]  */
	public const BIOME_ENTITIES = [
		Biome::OCEAN => [
			Squid::NETWORK_ID
			// TODO: water mobs
		],
		Biome::PLAINS => [
			Zombie::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID
		],
		Biome::DESERT => [
			Husk::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID
		],
		Biome::MOUNTAINS => [
			Zombie::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID
		],
		Biome::FOREST => [
			Zombie::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID
		],
		Biome::TAIGA => [
			Zombie::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID
		],
		Biome::SWAMP => [
			Zombie::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID
		],
		Biome::RIVER => [
			Squid::NETWORK_ID
			// TODO: water mobs
		],
		Biome::HELL => [
			ZombiePigman::NETWORK_ID,
			Ghast::NETWORK_ID
		],
		Biome::ICE_PLAINS => [
			Zombie::NETWORK_ID,
			Stray::NETWORK_ID,
			Creeper::NETWORK_ID
		],
		Biome::SMALL_MOUNTAINS => [
			Zombie::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID
		],
		Biome::BIRCH_FOREST => [
			Zombie::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID
		]
	];
}