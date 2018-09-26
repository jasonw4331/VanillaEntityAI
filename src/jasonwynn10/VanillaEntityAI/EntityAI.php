<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI;

use jasonwynn10\VanillaEntityAI\block\Ladder;
use jasonwynn10\VanillaEntityAI\block\MonsterSpawner;
use jasonwynn10\VanillaEntityAI\block\Vine;
use jasonwynn10\VanillaEntityAI\command\DifficultyCommand;
use jasonwynn10\VanillaEntityAI\command\SummonCommand;
use jasonwynn10\VanillaEntityAI\data\BiomeInfo;
use jasonwynn10\VanillaEntityAI\entity\hostile\Blaze;
use jasonwynn10\VanillaEntityAI\entity\hostile\CaveSpider;
use jasonwynn10\VanillaEntityAI\entity\hostile\Creeper;
use jasonwynn10\VanillaEntityAI\entity\hostile\ElderGuardian;
use jasonwynn10\VanillaEntityAI\entity\hostile\EnderDragon;
use jasonwynn10\VanillaEntityAI\entity\hostile\Enderman;
use jasonwynn10\VanillaEntityAI\entity\hostile\Endermite;
use jasonwynn10\VanillaEntityAI\entity\hostile\Evoker;
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
use jasonwynn10\VanillaEntityAI\entity\hostile\Vex;
use jasonwynn10\VanillaEntityAI\entity\hostile\Vindicator;
use jasonwynn10\VanillaEntityAI\entity\hostile\Witch;
use jasonwynn10\VanillaEntityAI\entity\hostile\Wither;
use jasonwynn10\VanillaEntityAI\entity\hostile\WitherSkeleton;
use jasonwynn10\VanillaEntityAI\entity\hostile\Zombie;
use jasonwynn10\VanillaEntityAI\entity\hostile\ZombieHorse;
use jasonwynn10\VanillaEntityAI\entity\hostile\ZombiePigman;
use jasonwynn10\VanillaEntityAI\entity\hostile\ZombieVillager;
use jasonwynn10\VanillaEntityAI\entity\neutral\AreaEffectCloud;
use jasonwynn10\VanillaEntityAI\entity\neutral\ArmorStand;
use jasonwynn10\VanillaEntityAI\entity\neutral\Arrow;
use jasonwynn10\VanillaEntityAI\entity\neutral\Boat;
use jasonwynn10\VanillaEntityAI\entity\neutral\ChestMinecart;
use jasonwynn10\VanillaEntityAI\entity\neutral\CommandBlockMinecart;
use jasonwynn10\VanillaEntityAI\entity\neutral\DangerousWitherSkull;
use jasonwynn10\VanillaEntityAI\entity\neutral\DragonFireball;
use jasonwynn10\VanillaEntityAI\entity\neutral\Egg;
use jasonwynn10\VanillaEntityAI\entity\neutral\EnderCrystal;
use jasonwynn10\VanillaEntityAI\entity\neutral\EnderPearl;
use jasonwynn10\VanillaEntityAI\entity\neutral\EvocationFang;
use jasonwynn10\VanillaEntityAI\entity\neutral\ExperienceBottle;
use jasonwynn10\VanillaEntityAI\entity\neutral\ExperienceOrb;
use jasonwynn10\VanillaEntityAI\entity\neutral\EyeOfEnder;
use jasonwynn10\VanillaEntityAI\entity\neutral\FallingBlock;
use jasonwynn10\VanillaEntityAI\entity\neutral\Fireball;
use jasonwynn10\VanillaEntityAI\entity\neutral\FireworksRocket;
use jasonwynn10\VanillaEntityAI\entity\neutral\FishingHook;
use jasonwynn10\VanillaEntityAI\entity\neutral\HopperMinecart;
use jasonwynn10\VanillaEntityAI\entity\neutral\Item;
use jasonwynn10\VanillaEntityAI\entity\neutral\LargeFireball;
use jasonwynn10\VanillaEntityAI\entity\neutral\LeashKnot;
use jasonwynn10\VanillaEntityAI\entity\neutral\Lightning;
use jasonwynn10\VanillaEntityAI\entity\neutral\LingeringPotion;
use jasonwynn10\VanillaEntityAI\entity\neutral\LlamaSpit;
use jasonwynn10\VanillaEntityAI\entity\neutral\Minecart;
use jasonwynn10\VanillaEntityAI\entity\neutral\MovingBlock;
use jasonwynn10\VanillaEntityAI\entity\neutral\Painting;
use jasonwynn10\VanillaEntityAI\entity\neutral\ShulkerBullet;
use jasonwynn10\VanillaEntityAI\entity\neutral\Snowball;
use jasonwynn10\VanillaEntityAI\entity\neutral\SplashPotion;
use jasonwynn10\VanillaEntityAI\entity\neutral\TNT;
use jasonwynn10\VanillaEntityAI\entity\neutral\TNTMinecart;
use jasonwynn10\VanillaEntityAI\entity\neutral\Trident;
use jasonwynn10\VanillaEntityAI\entity\neutral\TripodCamera;
use jasonwynn10\VanillaEntityAI\entity\neutral\WitherSkull;
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
use jasonwynn10\VanillaEntityAI\entity\passiveaggressive\IronGolem;
use jasonwynn10\VanillaEntityAI\entity\passiveaggressive\PolarBear;
use jasonwynn10\VanillaEntityAI\entity\passiveaggressive\SnowGolem;
use jasonwynn10\VanillaEntityAI\entity\passiveaggressive\Wolf;
use jasonwynn10\VanillaEntityAI\task\DespawnTask;
use jasonwynn10\VanillaEntityAI\task\HostileSpawnTask;
use jasonwynn10\VanillaEntityAI\task\InhabitedChunkCounter;
use jasonwynn10\VanillaEntityAI\task\PassiveSpawnTask;
use jasonwynn10\VanillaEntityAI\tile\MobSpawner;
use pocketmine\block\BlockFactory;
use pocketmine\entity\Entity;
use pocketmine\item\Armor;
use pocketmine\item\Book;
use pocketmine\item\Bow;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\FishingRod;
use pocketmine\item\ItemIds;
use pocketmine\item\Sword;
use pocketmine\item\TieredTool;
use pocketmine\item\Tool;
use pocketmine\level\biome\Biome;
use pocketmine\level\format\Chunk;
use pocketmine\level\Level;
use pocketmine\plugin\PluginBase;
use pocketmine\timings\TimingsHandler;
use spoondetector\SpoonDetector;

class EntityAI extends PluginBase {
	/** @var string[] $entities */
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
	/** @var self|null */
	private static $instance;

	/**
	 * @return self
	 */
	public static function getInstance(): self {
		return self::$instance;
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
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param Level $level
	 *
	 * @return int|null returns valid y coordinate if found
	 */
	public static function getSuitableHeight(int $x, int $y, int $z, Level $level) : ?int {
		$id = $level->getBlockIdAt($x, $y, $z);
		if($id === 0) {
			$air = true;
			--$y;
			while($air) {
				$id = $level->getBlockIdAt($x, $y, $z);
				if($id !== 0) { // this is an air block ...
					++$y;
					$air = false;
				}else {
					--$y;
					if($y < -255) {
						return null;
					}
				}
			}
		}else {
			$air = false;
			while(!$air) {
				$id = $level->getBlockIdAt($x, $y, $z);
				if($id === 0) {
					$air = true;
				}else {
					++$y;
					if($y > $level->getWorldHeight()) {
						return null;
					}
				}
			}
		}
		return $y;
	}

	/**
	 * @param int $entityId
	 * @param int $trialBiome
	 *
	 * @return bool
	 */
	public static function isSpawnAllowedByBiome(int $entityId, int $trialBiome): bool {
		return (in_array($entityId, BiomeInfo::ALLOWED_ENTITIES_BY_BIOME[$trialBiome]) or (($trialBiome !== Biome::HELL and $trialBiome !== 9) and in_array($entityId, BiomeInfo::OVERWORLD_BIOME_EXEMPT)));
	}

	public function onLoad(): void {
		self::$instance = $this;
		TimingsHandler::setEnabled();
	}

	public function onEnable(): void {
		if(!SpoonDetector::printSpoon($this, "spoon.txt"))
			return;
		BlockFactory::registerBlock(new Ladder(), true);
		BlockFactory::registerBlock(new MonsterSpawner(), true);
		BlockFactory::registerBlock(new Vine(), true);
		/** @noinspection PhpUnhandledExceptionInspection */
		MobSpawner::registerTile(MobSpawner::class, [MobSpawner::MOB_SPAWNER, "minecraft:mob_spawner"]);
		foreach(self::$entities as $class => $saveNames) {
			Entity::registerEntity($class, true, $saveNames);
		}
		$this->getServer()->getCommandMap()->register("pocketmine", new SummonCommand("summon"));
		$this->getServer()->getCommandMap()->register("pocketmine", new DifficultyCommand("difficulty"));
		new EntityListener($this);
		if($this->getServer()->getConfigBool("spawn-mobs", true)) {
			$this->getScheduler()->scheduleRepeatingTask(new HostileSpawnTask(), 1);
		}
		if($this->getServer()->getConfigBool("spawn-animals", true)) {
			$this->getScheduler()->scheduleRepeatingTask(new PassiveSpawnTask(), 20);
		}
		$this->getScheduler()->scheduleRepeatingTask(new DespawnTask(), 1);
		$this->getScheduler()->scheduleRepeatingTask(new InhabitedChunkCounter(), 20 * 60 * 60);
	}

	/**
	 * @param Level $level
	 * @param Chunk $chunk
	 *
	 * @return float
	 */
	public function getClumpedRegionalDifficulty(Level $level, Chunk $chunk): float {
		$regionalDifficulty = $this->getRegionalDifficulty($level, $chunk);
		if($regionalDifficulty < 2.0) {
			$result = 0.0;
		}elseif($regionalDifficulty > 4.0) {
			$result = 1.0;
		}else {
			$result = ($regionalDifficulty - 2.0) / 2.0;
		}
		return $result;
	}

	/**
	 * @param Level $level
	 * @param Chunk $chunk
	 *
	 * @return float
	 */
	public function getRegionalDifficulty(Level $level, Chunk $chunk): float {
		$totalPlayTime = 0;
		foreach($level->getPlayers() as $player) {
			$time = (microtime(true) - $player->creationTime);
			$hours = 0;
			if($time >= 3600) {
				$hours = floor(($time % (3600 * 24)) / 3600);
			}
			$totalPlayTime += $hours;
		}
		if($totalPlayTime > 21) {
			$totalTimeFactor = 0.25;
		}elseif($totalPlayTime < 20) {
			$totalTimeFactor = 0;
		}else {
			$totalTimeFactor = (($totalPlayTime * 20 * 60 * 60) - 72000) / 5760000;
		}
		$chunkInhabitedTime = isset($chunk->inhabitedTime) ? $chunk->inhabitedTime : 0;
		if($chunkInhabitedTime > 50) {
			$chunkFactor = 1;
		}else {
			$chunkFactor = ($chunkInhabitedTime * 20 * 60 * 60) / 3600000;
		}
		if($level->getDifficulty() !== Level::DIFFICULTY_HARD) {
			$chunkFactor *= 3 / 4;
		}
		$phaseTime = $level->getTime() / Level::TIME_FULL;
		while($phaseTime > 5)
			$phaseTime -= 5; // TODO: better method
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
		if($moonPhase / 4 > $totalTimeFactor) {
			$chunkFactor += $totalTimeFactor;
		}else {
			$chunkFactor += $moonPhase / 4;
		}
		if($level->getDifficulty() === Level::DIFFICULTY_EASY) {
			$chunkFactor /= 2;
		}
		$regionalDifficulty = 0.75 + $totalTimeFactor + $chunkFactor;
		if($level->getDifficulty() === Level::DIFFICULTY_NORMAL) {
			$regionalDifficulty *= 2;
		}
		if($level->getDifficulty() === Level::DIFFICULTY_HARD) {
			$regionalDifficulty *= 3;
		}
		return $regionalDifficulty;
	}

	/**
	 * @param int $experienceLevel
	 * @param \pocketmine\item\Item $item
	 *
	 * @return EnchantmentInstance
	 */
	public function getRandomEnchantment(int $experienceLevel, \pocketmine\item\Item $item): EnchantmentInstance {
		$return = new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SHARPNESS)); // default
		if($experienceLevel <= 8) {
			$bookShelves = 0;
		}elseif($experienceLevel <= 9) {
			$bookShelves = 1;
		}elseif($experienceLevel <= 11) {
			$bookShelves = 2;
		}elseif($experienceLevel <= 12) {
			$bookShelves = 3;
		}elseif($experienceLevel <= 14) {
			$bookShelves = 4;
		}elseif($experienceLevel <= 15) {
			$bookShelves = 5;
		}elseif($experienceLevel <= 17) {
			$bookShelves = 6;
		}elseif($experienceLevel <= 18) {
			$bookShelves = 7;
		}elseif($experienceLevel <= 20) {
			$bookShelves = 8;
		}elseif($experienceLevel <= 21) {
			$bookShelves = 9;
		}elseif($experienceLevel <= 23) {
			$bookShelves = 10;
		}elseif($experienceLevel <= 24) {
			$bookShelves = 11;
		}elseif($experienceLevel <= 26) {
			$bookShelves = 12;
		}elseif($experienceLevel <= 27) {
			$bookShelves = 13;
		}elseif($experienceLevel <= 29) {
			$bookShelves = 14;
		}elseif($experienceLevel <= 30) {
			$bookShelves = 15;
		}else {
			$bookShelves = 15;
		}
		if($item instanceof TieredTool) {
			switch($item->getTier()) {
				case TieredTool::TIER_WOODEN:
					$enchantability = 15;
				break;
				case TieredTool::TIER_STONE:
					$enchantability = 5;
				break;
				case TieredTool::TIER_IRON:
					$enchantability = 14;
				break;
				case TieredTool::TIER_GOLD:
					$enchantability = 22;
				break;
				case TieredTool::TIER_DIAMOND:
					$enchantability = 10;
				break;
				default:
					$enchantability = 14; // default to iron
				break;
			}
		}elseif($item instanceof Tool) {
			$enchantability = 14; // default to iron
		}elseif($item instanceof FishingRod) {
			$enchantability = 14; // default to iron
		}elseif($item instanceof Armor) {
			if($item->getId() === ItemIds::LEATHER_BOOTS or $item->getId() === ItemIds::LEATHER_LEGGINGS or $item->getId() === ItemIds::LEATHER_CHESTPLATE or $item->getId() === ItemIds::LEATHER_HELMET) {
				$enchantability = 15;
			}elseif($item->getId() === ItemIds::CHAIN_BOOTS or $item->getId() === ItemIds::CHAIN_LEGGINGS or $item->getId() === ItemIds::CHAIN_CHESTPLATE or $item->getId() === ItemIds::CHAIN_HELMET) {
				$enchantability = 12;
			}elseif($item->getId() === ItemIds::IRON_BOOTS or $item->getId() === ItemIds::IRON_LEGGINGS or $item->getId() === ItemIds::IRON_CHESTPLATE or $item->getId() === ItemIds::IRON_HELMET) {
				$enchantability = 9;
			}elseif($item->getId() === ItemIds::GOLD_BOOTS or $item->getId() === ItemIds::GOLD_LEGGINGS or $item->getId() === ItemIds::GOLD_CHESTPLATE or $item->getId() === ItemIds::GOLD_HELMET) {
				$enchantability = 25;
			}elseif($item->getId() === ItemIds::DIAMOND_BOOTS or $item->getId() === ItemIds::DIAMOND_LEGGINGS or $item->getId() === ItemIds::DIAMOND_CHESTPLATE or $item->getId() === ItemIds::DIAMOND_HELMET) {
				$enchantability = 10;
			}else {
				$enchantability = 9; // default to iron
			}
		}elseif($item instanceof Book) {
			$enchantability = 1;
		}else {
			throw new \RuntimeException("Cannot enchant that item");
		}
		$baseEnchantmentLevel = (mt_rand(1, 8) + floor($bookShelves / 2) + mt_rand(0, $bookShelves));
		$topSlotEnchantmentLevel = max($baseEnchantmentLevel / 3, 1);
		$middleSlotEnchantmentLevel = ($baseEnchantmentLevel * 2) / 3 + 1;
		$bottomSlotEnchantmentLevel = max($baseEnchantmentLevel, $bookShelves * 2);
		$modifiedEnchantmentLevel = $baseEnchantmentLevel + mt_rand(0, $enchantability / 4) + mt_rand(0, $enchantability / 4) + 1;
		$randomEnchantability = 1 + mt_rand(($enchantability / 2) / 2 + 1, (($enchantability / 2) / 2 + 1) - 1) + mt_rand(($enchantability / 2) / 2 + 1, (($enchantability / 2) / 2 + 1) - 1);
		switch(mt_rand(1, 3)) {
			default:
			case 1:
				$chosenEnchantmentLevel = $topSlotEnchantmentLevel;
			break;
			case 2:
				$chosenEnchantmentLevel = $middleSlotEnchantmentLevel;
			break;
			case 3:
				$chosenEnchantmentLevel = $bottomSlotEnchantmentLevel;
			break;
		}
		$totalLevel = $chosenEnchantmentLevel + $randomEnchantability;
		$randomBonus = 1 + (lcg_value() + lcg_value() - 1) * 0.15;
		$finalLevel = (int)($totalLevel * $randomBonus + 0.5);
		if($finalLevel < 1) {
			$finalLevel = 1;
		}
		$enchantments = [];
		if($item instanceof Sword or $item instanceof Book) {
			$enchantments[Enchantment::SHARPNESS] = 10;
			$enchantments[Enchantment::BANE_OF_ARTHROPODS] = 5;
			$enchantments[Enchantment::KNOCKBACK] = 5;
			$enchantments[Enchantment::SMITE] = 5;
			$enchantments[Enchantment::FIRE_ASPECT] = 2;
			$enchantments[Enchantment::LOOTING] = 2;
		}
		if(($item instanceof Tool and !$item instanceof Sword) or $item instanceof Book) {
			$enchantments[Enchantment::EFFICIENCY] = 10;
			$enchantments[Enchantment::FORTUNE] = 2;
			$enchantments[Enchantment::SILK_TOUCH] = 1;
		}
		if($item instanceof Armor or $item instanceof Book) {
			$enchantments[Enchantment::PROTECTION] = 10;
			$enchantments[Enchantment::BINDING] = 1;
			$enchantments[Enchantment::FIRE_PROTECTION] = 5;
			$enchantments[Enchantment::PROJECTILE_PROTECTION] = 5;
			$enchantments[Enchantment::BLAST_PROTECTION] = 2;
			$enchantments[Enchantment::THORNS] = 1;
			if($item->getId() === ItemIds::LEATHER_BOOTS or $item->getId() === ItemIds::CHAIN_BOOTS or $item->getId() === ItemIds::IRON_BOOTS or $item->getId() === ItemIds::GOLD_BOOTS or $item->getId() === ItemIds::DIAMOND_BOOTS) {
				$enchantments[Enchantment::FEATHER_FALLING] = 5;
				$enchantments[Enchantment::FROST_WALKER] = 2;
				$enchantments[Enchantment::DEPTH_STRIDER] = 2;
			}elseif($item->getId() === ItemIds::LEATHER_HELMET or $item->getId() === ItemIds::CHAIN_HELMET or $item->getId() === ItemIds::IRON_HELMET or $item->getId() === ItemIds::GOLD_HELMET or $item->getId() === ItemIds::DIAMOND_HELMET) {
				$enchantments[Enchantment::RESPIRATION] = 2;
				$enchantments[Enchantment::AQUA_AFFINITY] = 2;
			}
		}
		if($item instanceof Bow or $item instanceof Book) {
			$enchantments[Enchantment::POWER] = 10;
			$enchantments[Enchantment::FLAME] = 2;
			$enchantments[Enchantment::PUNCH] = 2;
			$enchantments[Enchantment::INFINITY] = 1;
		}
		if($item instanceof FishingRod or $item instanceof Book) {
			$enchantments[Enchantment::LUCK_OF_THE_SEA] = 2;
			$enchantments[Enchantment::LURE] = 2;
		}
		if($item instanceof Durable or $item instanceof Book) {
			$enchantments[Enchantment::UNBREAKING] = 5;
			$enchantments[Enchantment::MENDING] = 2;
		}
		$enchantments[Enchantment::VANISHING] = 1;
		$enchantments = array_filter($enchantments, function($id) { // TODO: remove when all enchantments implemented
			return Enchantment::getEnchantment($id) !== null;
		}, ARRAY_FILTER_USE_KEY); // filter unregistered enchantments
		$totalWeight = 0;
		foreach($enchantments as $weight) {
			$totalWeight += $weight;
		}
		$random = mt_rand(1, $totalWeight);
		foreach($enchantments as $id => $weight) {
			if($random - $weight <= 0) {
				$return = new EnchantmentInstance(Enchantment::getEnchantment($id));
				$return->setLevel($finalLevel);
				break;
			}
		}
		// TODO: filter valid enchantments based on $modifiedEnchantmentLevel https://minecraft.gamepedia.com/Enchanting/Levels
		return $return;
	}
}