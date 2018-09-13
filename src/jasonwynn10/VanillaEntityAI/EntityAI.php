<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI;

use jasonwynn10\VanillaEntityAI\command\SummonCommand;
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
use jasonwynn10\VanillaEntityAI\entity\neutral\Item;
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
use jasonwynn10\VanillaEntityAI\task\InhabitedChunkCounter;
use jasonwynn10\VanillaEntityAI\task\PassiveSpawnTask;
use pocketmine\entity\Entity;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\level\format\Chunk;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\plugin\PluginBase;
use spoondetector\SpoonDetector;

class EntityAI extends PluginBase {

	public static $entities = [
		Chicken::class => ['Chicken', 'minecraft:chicken'],
		Cow::class => ['Cow', 'minecraft:cow'],
		Pig::class => ['Pig', 'minecraft:pig'],
		Sheep::class => ['sheep', 'minecraft:sheep'],
		Wolf::class => ['Wolf', 'minecraft:wolf'],
		Villager::class => ['Villager', 'minecraft:villager'],
		Mooshroom::class => ['Mooshroom', 'minecraft:mooshroom'],
		Squid::class => ['Squid', 'minecraft:squid'],
		Rabbit::class => ['Rabbit', 'minecraft:rabbit'],
		Bat::class => ['Bat', 'minecraft:bat'],
		IronGolem::class => ['IronGolem', 'minecraft:irongolem'],
		SnowGolem::class => ['SnowGolem', 'minecraft:snowgolem'],
		Ocelot::class => ['Ocelot', 'minecraft:ocelot'],
		Horse::class => ['Horse', 'minecraft:horse'],
		Donkey::class => ['Donkey', 'minecraft:donkey'],
		Mule::class => ['Mule', 'minecraft:mule'],
		SkeletonHorse::class => ['SkeletonHorse', 'minecraft:skeletonhorse'],
		ZombieHorse::class => ['ZombieHorse', 'minecraft:zombiehorse'],
		PolarBear::class => ['PolarBear', 'minecraft:polarbear'],
		Llama::class => ['Llama', 'minecraft:llama'],
		Parrot::class => ['Parrot', 'minecraft:parrot'],
		Dolphin::class => ['Dolphin', 'minecraft:dolphin'],
		Zombie::class => ['Zombie', 'minecraft:zombie'],
		Creeper::class => ['Creeper', 'minecraft:creeper'],
		Skeleton::class => ['Skeleton', 'minecraft:skeleton'],
		Spider::class => ['Spider', 'minecraft:spider'],
		ZombiePigman::class => ['PigZombie', 'minecraft:pigzombie'],
		Slime::class => ['Slime', 'minecraft:slime'],
		Enderman::class => ['Enderman', 'minecraft:enderman'],
		Silverfish::class => ['Silverfish', 'minecraft:silverfish'],
		CaveSpider::class => ['CaveSpider', 'minecraft:cavespider'],
		Ghast::class => ['Ghast', 'minecraft:ghast'],
		MagmaCube::class => ['MagmaCube', 'minecraft:magmacube'],
		Blaze::class => ['Blaze', 'minecraft:blaze'],
		ZombieVillager::class => ['ZombieVillager', 'minecraft:zombievillager'],
		Witch::class => ['Witch', 'minecraft:witch'],
		Stray::class => ['Stray', 'minecraft:stray'],
		Husk::class => ['Husk', 'minecraft:husk'],
		WitherSkeleton::class => ['WitherSkeleton', 'minecraft:witherskeleton'],
		Guardian::class => ['Guardian', 'minecraft:guardian'],
		ElderGuardian::class => ['ElderGuardian', 'minecraft:elderguardian'],
		//NPC
		Wither::class => ['Wither', 'minecraft:wither'],
		EnderDragon::class => ['EnderDragon', 'minecraft:enderdragon'],
		Shulker::class => ['Shulker', 'minecraft:shulker'],
		Endermite::class => ['Endermite', 'minecraft:endermite'],
		//Learn to code mascot
		Vindicator::class => ['Vindicator', 'minecraft:vindicator'],
		//
		//ArmorStand::class => [],
		//TripodCamera::class => [],
		// player
		Item::class => ['Item', 'minecraft:item'],
		//TNT::class => [],
		//FallingBlock::class => [],
		//MovingBlock::class => [],
		//ExperienceBottle::class => [],
		//ExperienceOrb::class => [],
		//EyeOfEnder::class => [],
		//EnderCrystal::class => ['EnderCrystal', 'minecraft:ender_crystal'],
		//FireworksRocket::class => ['FireworksRocket',	'minecraft:fireworks_rocket'],
		//Trident::class => ['Thrown Trident', 'minecraft:thrown_trident'],
		//
		//ShulkerBullet::class => [],
		//FishingHook::class => ['FishingHook', 'minecraft:fishinghook'],
		//chalkboard
		//DragonFireball::class => [],
		//Arrow::class => [],
		//Snowball::class => [],
		//Egg::class => [],
		//Painting::class => [],
		//Minecart::class => ['Minecart', 'minecraft:minecart'],
		//LargeFireball::class => [],
		//SplashPotion::class => [],
		//EnderPearl::class => [],
		//LeashKnot::class => [],
		//WitherSkull::class => [],
		//Boat::class => [],
		//DangerousWitherSkull::class => [],
		//Lightning::class => [],
		//Fireball::class => [],
		//AreaEffectCloud::class => [],
		//HopperMinecart::class => [],
		//TNTMinecart::class => [],
		//ChestMinecart::class => [],
		//
		//CommandBlockMinecart::class => [],
		//LingeringPotion::class => [],
		//LlamaSpit::class => [],
		//EvocationFang::class => [],
		//Evoker::class => [],
		//Vex::class => [],
		//ice bomb
		//balloon
		//pufferfish
		//salmon
		//drowned
		//tropical fish
		//fish
	];

	private static $instance;

	/**
	 * @return self
	 */
	public static function getInstance() : self {
		return self::$instance;
	}

	public function onLoad() : void {
		self::$instance = $this;
	}

	public function onEnable() : void {
		if(!SpoonDetector::printSpoon($this, "spoon.txt"))
			return;

		foreach(self::$entities as $class => $saveNames) {
			Entity::registerEntity($class, true, $saveNames);
		}

		$this->getServer()->getCommandMap()->register("pocketmine", new SummonCommand("summon"));

		new EntityListener($this);

		if($this->getServer()->getConfigBool("spawn-mobs", true))
			$this->getScheduler()->scheduleRepeatingTask(new HostileSpawnTask(), 1);
		if($this->getServer()->getConfigBool("spawn-animals", true))
			$this->getScheduler()->scheduleRepeatingTask(new PassiveSpawnTask(), 20);
		$this->getScheduler()->scheduleRepeatingTask(new DespawnTask(), 20);
		$this->getScheduler()->scheduleRepeatingTask(new InhabitedChunkCounter(), 20 * 60 * 60);
		// TODO: mob crush limit task?
	}

	/**
	 * @param Level $level
	 * @param Chunk $chunk
	 *
	 * @return float
	 */
	public function getRegionalDifficulty(Level $level, Chunk $chunk) : float {
		$totalPlayTime = 0;
		foreach($level->getPlayers() as $player) {
			$time = (microtime(true) - $player->creationTime);
			$hours = 0;
			if($time >= 3600) {
				$hours = floor(($time % (3600 * 24)) / 3600);
			}
			$totalPlayTime += $hours;
		}

		if ($totalPlayTime > 21)
			$totalTimeFactor = 0.25;
		elseif($totalPlayTime < 20)
			$totalTimeFactor = 0;
		else
			$totalTimeFactor = (($totalPlayTime * 20 * 60 * 60) - 72000 ) / 5760000;

		$chunkInhabitedTime = isset($chunk->inhabitedTime) ? $chunk->inhabitedTime : 0;

		if($chunkInhabitedTime > 50)
			$chunkFactor = 1;
		else
			$chunkFactor = ($chunkInhabitedTime * 20 * 60 * 60) / 3600000;

		if($level->getDifficulty() !== Level::DIFFICULTY_HARD)
			$chunkFactor *= 3/4;

		$phaseTime = $level->getTime() / Level::TIME_FULL;
		while($phaseTime > 5)
			$phaseTime-=5; // TODO: better method
		$moonPhase = 1.0;
		switch($phaseTime) {
			case 1:
				$moonPhase = 1.0;
				break;
			case 2:
				$moonPhase = 0.75;
				break;
			case 3:
				$moonPhase = 0.5;
				break;
			case 4:
				$moonPhase = 0.25;
				break;
			case 5:
				$moonPhase = 0.0;
				break;
		}

		if($moonPhase / 4 > $totalTimeFactor)
			$chunkFactor += $totalTimeFactor;
		else
			$chunkFactor += $moonPhase / 4;

		if($level->getDifficulty() === Level::DIFFICULTY_EASY)
			$chunkFactor /= 2;

		$regionalDifficulty = 0.75 + $totalTimeFactor + $chunkFactor;

		if($level->getDifficulty() === Level::DIFFICULTY_NORMAL)
			$regionalDifficulty *= 2;
		if ($level->getDifficulty() === Level::DIFFICULTY_HARD)
			$regionalDifficulty *= 3;

		return $regionalDifficulty;
	}

	/**
	 * @param Level $level
	 * @param Chunk $chunk
	 *
	 * @return float
	 */
	public function getClumpedRegionalDifficulty(Level $level, Chunk $chunk) : float {
		$regionalDifficulty = $this->getRegionalDifficulty($level, $chunk);
		if ($regionalDifficulty < 2.0) {
			$result = 0.0;
		}elseif($regionalDifficulty > 4.0) {
			$result = 1.0;
		}else{
			$result = ($regionalDifficulty - 2.0) / 2.0;
		}
		return $result;
	}

	/**
	 * @param int $experienceLevel
	 *
	 * @return EnchantmentInstance
	 */
	public function getRandomEnchantment(int $experienceLevel) : EnchantmentInstance {
		// TODO: vanilla enchantment math
	}

	/**
	 * Returns a suitable Y-position for spawning an entity, starting from the given coordinates.
	 *
	 * First, it's checked if the given position is AIR position. If so, we search down the y-coordinate
	 * to get a first non-air block. When a non-air block is found the position returned is the last found air
	 * position.
	 *
	 * When the given coordinates are NOT an AIR block coordinate we search upwards until the first air block is found
	 * which is then returned to the caller.
	 *
	 * @param       $x                int the x position to start search
	 * @param       $y                int the y position to start search
	 * @param       $z                int the z position to start searching
	 * @param Level $level Level the level object to search in
	 *
	 * @return null|Position    either NULL if no valid position was found or the final AIR spawn position
	 */
	public static function getSuitableHeightPosition($x, $y, $z, Level $level) {
		$newPosition = null;
		$id = $level->getBlockIdAt($x, $y, $z);
		if($id == 0) { // we found an air block - we need to search down step by step to get the correct block which is not an "AIR" block
			$air = true;
			$y = $y - 1;
			while($air) {
				$id = $level->getBlockIdAt($x, $y, $z);
				if($id != 0) { // this is an air block ...
					$newPosition = new Position($x, $y + 1, $z, $level);
					$air = false;
				}else{
					$y = $y - 1;
					if($y < -255) {
						break;
					}
				}
			}
		}else{ // something else than AIR block. search upwards for a valid air block
			$air = false;
			while(!$air) {
				$id = $level->getBlockIdAt($x, $y, $z);
				if($id == 0) { // this is an air block ...
					$newPosition = new Position($x, $y, $z, $level);
					$air = true;
				}else{
					$y = $y + 1;
					if($y > 255) {
						break;
					}
				}
			}
		}

		return $newPosition;
	}


}