<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\data;

use jasonwynn10\VanillaEntityAI\entity\hostile\Creeper;
use jasonwynn10\VanillaEntityAI\entity\hostile\Drowned;
use jasonwynn10\VanillaEntityAI\entity\hostile\Ghast;
use jasonwynn10\VanillaEntityAI\entity\hostile\MagmaCube;
use jasonwynn10\VanillaEntityAI\entity\hostile\Skeleton;
use jasonwynn10\VanillaEntityAI\entity\hostile\Slime;
use jasonwynn10\VanillaEntityAI\entity\hostile\Spider;
use jasonwynn10\VanillaEntityAI\entity\hostile\Stray;
use jasonwynn10\VanillaEntityAI\entity\hostile\Witch;
use jasonwynn10\VanillaEntityAI\entity\hostile\Zombie;
use jasonwynn10\VanillaEntityAI\entity\hostile\ZombiePigman;
use jasonwynn10\VanillaEntityAI\entity\passive\Chicken;
use jasonwynn10\VanillaEntityAI\entity\passive\Cow;
use jasonwynn10\VanillaEntityAI\entity\passive\Dolphin;
use jasonwynn10\VanillaEntityAI\entity\passive\Donkey;
use jasonwynn10\VanillaEntityAI\entity\passive\Horse;
use jasonwynn10\VanillaEntityAI\entity\passive\Llama;
use jasonwynn10\VanillaEntityAI\entity\passive\Pig;
use jasonwynn10\VanillaEntityAI\entity\passive\Rabbit;
use jasonwynn10\VanillaEntityAI\entity\passive\Sheep;
use jasonwynn10\VanillaEntityAI\entity\passive\Squid;
use jasonwynn10\VanillaEntityAI\entity\passiveaggressive\PolarBear;
use pocketmine\level\biome\Biome;

class BiomeEntityList {
	/** @var int[][]  */
	public const BIOME_ENTITIES = [
		Biome::OCEAN => [
			Squid::NETWORK_ID,
			Drowned::NETWORK_ID
			// TODO: water mobs
		],
		Biome::PLAINS => [
			Zombie::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID,
			Witch::NETWORK_ID
		],
		Biome::DESERT => [
			Zombie::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID,
			Witch::NETWORK_ID
		],
		Biome::MOUNTAINS => [
			Zombie::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID,
			Witch::NETWORK_ID
		],
		Biome::FOREST => [
			Zombie::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID,
			Witch::NETWORK_ID
		],
		Biome::TAIGA => [
			Zombie::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID,
			Witch::NETWORK_ID
		],
		Biome::SWAMP => [
			Zombie::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID,
			Witch::NETWORK_ID
		],
		Biome::RIVER => [
			Squid::NETWORK_ID,
			Drowned::NETWORK_ID
			// TODO: water mobs
		],
		Biome::HELL => [
			ZombiePigman::NETWORK_ID,
			Ghast::NETWORK_ID,
			MagmaCube::NETWORK_ID
		],
		Biome::ICE_PLAINS => [
			Zombie::NETWORK_ID,
			Stray::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID,
			Witch::NETWORK_ID
		],
		Biome::SMALL_MOUNTAINS => [
			Zombie::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID,
			Witch::NETWORK_ID
		],
		Biome::BIRCH_FOREST => [
			Zombie::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID,
			Witch::NETWORK_ID
		]
	];
	public const BIOME_HOSTILE_MOBS = [
		Biome::OCEAN => [
			Drowned::NETWORK_ID
			// TODO: water mobs
		],
		Biome::PLAINS => [
			Zombie::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID,
			Witch::NETWORK_ID
		],
		Biome::DESERT => [
			Zombie::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID,
			Witch::NETWORK_ID
		],
		Biome::MOUNTAINS => [
			Zombie::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID,
			Witch::NETWORK_ID
		],
		Biome::FOREST => [
			Zombie::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID,
			Witch::NETWORK_ID
		],
		Biome::TAIGA => [
			Zombie::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID,
			Witch::NETWORK_ID
		],
		Biome::SWAMP => [
			Zombie::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID,
			Slime::NETWORK_ID,
			Witch::NETWORK_ID
		],
		Biome::RIVER => [
			Drowned::NETWORK_ID
			// TODO: water mobs
		],
		Biome::HELL => [
			ZombiePigman::NETWORK_ID,
			Ghast::NETWORK_ID,
			MagmaCube::NETWORK_ID
		],
		Biome::ICE_PLAINS => [
			Zombie::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID,
			Witch::NETWORK_ID
		],
		Biome::SMALL_MOUNTAINS => [
			Zombie::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID,
			Witch::NETWORK_ID
		],
		Biome::BIRCH_FOREST => [
			Zombie::NETWORK_ID,
			Skeleton::NETWORK_ID,
			Creeper::NETWORK_ID,
			Spider::NETWORK_ID,
			Witch::NETWORK_ID
		]
	];
	public const BIOME_ANIMALS = [
		Biome::OCEAN => [
			Squid::NETWORK_ID,
			Dolphin::NETWORK_ID
			// TODO: water mobs
		],
		Biome::PLAINS => [
			Cow::NETWORK_ID,
			Pig::NETWORK_ID,
			Sheep::NETWORK_ID,
			Chicken::NETWORK_ID,
			Horse::NETWORK_ID,
			Donkey::NETWORK_ID,
			Rabbit::NETWORK_ID
		],
		Biome::DESERT => [
			Cow::NETWORK_ID,
			Pig::NETWORK_ID,
			Sheep::NETWORK_ID,
			Chicken::NETWORK_ID
		],
		Biome::MOUNTAINS => [
			Cow::NETWORK_ID,
			Pig::NETWORK_ID,
			Sheep::NETWORK_ID,
			Chicken::NETWORK_ID,
			Llama::NETWORK_ID
		],
		Biome::FOREST => [
			Cow::NETWORK_ID,
			Pig::NETWORK_ID,
			Sheep::NETWORK_ID,
			Chicken::NETWORK_ID
		],
		Biome::TAIGA => [
			Cow::NETWORK_ID,
			Pig::NETWORK_ID,
			Sheep::NETWORK_ID,
			Chicken::NETWORK_ID
		],
		Biome::SWAMP => [
			Cow::NETWORK_ID,
			Pig::NETWORK_ID,
			Sheep::NETWORK_ID,
			Chicken::NETWORK_ID
		],
		Biome::RIVER => [
			Squid::NETWORK_ID
			// TODO: fish
		],
		Biome::HELL => [
			// none spawn
		],
		Biome::ICE_PLAINS => [
			Cow::NETWORK_ID,
			Pig::NETWORK_ID,
			Sheep::NETWORK_ID,
			Chicken::NETWORK_ID,
			PolarBear::NETWORK_ID
		],
		Biome::SMALL_MOUNTAINS => [
			Cow::NETWORK_ID,
			Pig::NETWORK_ID,
			Sheep::NETWORK_ID,
			Chicken::NETWORK_ID
		],
		Biome::BIRCH_FOREST => [
			Cow::NETWORK_ID,
			Pig::NETWORK_ID,
			Sheep::NETWORK_ID,
			Chicken::NETWORK_ID
		]
	];
}