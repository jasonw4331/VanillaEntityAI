<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI;

use jasonwynn10\VanillaEntityAI\entity\hostile\Blaze;
use jasonwynn10\VanillaEntityAI\entity\hostile\CaveSpider;
use jasonwynn10\VanillaEntityAI\entity\hostile\Creeper;
use jasonwynn10\VanillaEntityAI\entity\hostile\ElderGuardian;
use jasonwynn10\VanillaEntityAI\entity\hostile\EnderDragon;
use jasonwynn10\VanillaEntityAI\entity\hostile\Enderman;
use jasonwynn10\VanillaEntityAI\entity\hostile\Endermite;
use jasonwynn10\VanillaEntityAI\entity\hostile\Ghast;
use jasonwynn10\VanillaEntityAI\entity\hostile\Guardian;
use jasonwynn10\VanillaEntityAI\entity\hostile\Husk;
use jasonwynn10\VanillaEntityAI\entity\hostile\MagmaCube;
use jasonwynn10\VanillaEntityAI\entity\hostile\Shulker;
use jasonwynn10\VanillaEntityAI\entity\hostile\Silverfish;
use jasonwynn10\VanillaEntityAI\entity\hostile\Skeleton;
use jasonwynn10\VanillaEntityAI\entity\hostile\Slime;
use jasonwynn10\VanillaEntityAI\entity\hostile\Spider;
use jasonwynn10\VanillaEntityAI\entity\hostile\Stray;
use jasonwynn10\VanillaEntityAI\entity\hostile\Vindicator;
use jasonwynn10\VanillaEntityAI\entity\hostile\Witch;
use jasonwynn10\VanillaEntityAI\entity\hostile\Wither;
use jasonwynn10\VanillaEntityAI\entity\hostile\WitherSkeleton;
use jasonwynn10\VanillaEntityAI\entity\hostile\Zombie;
use jasonwynn10\VanillaEntityAI\entity\hostile\ZombiePigman;
use jasonwynn10\VanillaEntityAI\entity\hostile\ZombieVillager;
use jasonwynn10\VanillaEntityAI\entity\passive\Bat;
use jasonwynn10\VanillaEntityAI\entity\passive\Chicken;
use jasonwynn10\VanillaEntityAI\entity\passive\Cow;
use jasonwynn10\VanillaEntityAI\entity\passive\Dolphin;
use jasonwynn10\VanillaEntityAI\entity\passive\Donkey;
use jasonwynn10\VanillaEntityAI\entity\passive\Horse;
use jasonwynn10\VanillaEntityAI\entity\passive\Llama;
use jasonwynn10\VanillaEntityAI\entity\passive\Mooshroom;
use jasonwynn10\VanillaEntityAI\entity\passive\Mule;
use jasonwynn10\VanillaEntityAI\entity\passive\Ocelot;
use jasonwynn10\VanillaEntityAI\entity\passive\Parrot;
use jasonwynn10\VanillaEntityAI\entity\passive\Pig;
use jasonwynn10\VanillaEntityAI\entity\passive\Rabbit;
use jasonwynn10\VanillaEntityAI\entity\passive\Sheep;
use jasonwynn10\VanillaEntityAI\entity\passive\SkeletonHorse;
use jasonwynn10\VanillaEntityAI\entity\passive\Squid;
use jasonwynn10\VanillaEntityAI\entity\passive\Villager;
use jasonwynn10\VanillaEntityAI\entity\passive\ZombieHorse;
use jasonwynn10\VanillaEntityAI\entity\passiveaggressive\IronGolem;
use jasonwynn10\VanillaEntityAI\entity\passiveaggressive\PolarBear;
use jasonwynn10\VanillaEntityAI\entity\passiveaggressive\SnowGolem;
use jasonwynn10\VanillaEntityAI\entity\passiveaggressive\Wolf;
use jasonwynn10\VanillaEntityAI\task\DespawnTask;
use jasonwynn10\VanillaEntityAI\task\HostileSpawnTask;
use jasonwynn10\VanillaEntityAI\task\PassiveSpawnTask;
use pocketmine\entity\Entity;
use pocketmine\plugin\PluginBase;
use spoondetector\SpoonDetector;

class EntityAI extends PluginBase {

	public static $entities = [
		Chicken::class => ['Chicken', 'minecraft:chicken'],
		Cow::class => ['Cow', 'minecraft:cow'],
		Pig::class => ['Pig', 'minecraft:pig'],
		Sheep::class => [],
		Wolf::class => [],
		Villager::class => [],
		Mooshroom::class => [],
		Squid::class => [],
		Rabbit::class => [],
		Bat::class => [],
		IronGolem::class => [],
		SnowGolem::class => [],
		Ocelot::class => [],
		Horse::class => [],
		Donkey::class => [],
		Mule::class => [],
		SkeletonHorse::class => [],
		ZombieHorse::class => [],
		PolarBear::class => [],
		Llama::class => [],
		Parrot::class => [],
		Dolphin::class => [],
		Zombie::class => ['Zombie', 'minecraft:zombie'],
		Creeper::class => [],
		Skeleton::class => [],
		Spider::class => [],
		ZombiePigman::class => [],
		Slime::class => [],
		Enderman::class => [],
		Silverfish::class => [],
		CaveSpider::class => [],
		Ghast::class => [],
		MagmaCube::class => [],
		Blaze::class => [],
		ZombieVillager::class => [],
		Witch::class => [],
		Stray::class => [],
		Husk::class => [],
		WitherSkeleton::class => [],
		Guardian::class => [],
		ElderGuardian::class => [],
		//NPC
		Wither::class => [],
		EnderDragon::class => [],
		Shulker::class => [],
		Endermite::class => [],
		Vindicator::class => [],
		//Learn to code mascot
		];

	public function onLoad() : void {
		foreach(self::$entities as $class => $saveNames) {
			Entity::registerEntity($class, true, $saveNames);
		}
		// TODO: find a way to save entities across server restarts, but unload with chunks in-game
	}

	public function onEnable() : void {
		//SpoonDetector::printSpoon($this, "spoon.txt");
		new EntityListener($this);
		$this->getScheduler()->scheduleRepeatingTask(new HostileSpawnTask(), 1);
		$this->getScheduler()->scheduleRepeatingTask(new PassiveSpawnTask(), 400);
		$this->getScheduler()->scheduleRepeatingTask(new DespawnTask(), 1);
	}
}