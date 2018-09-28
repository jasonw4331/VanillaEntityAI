<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\hostile;

use jasonwynn10\VanillaEntityAI\entity\Collidable;
use jasonwynn10\VanillaEntityAI\entity\CollisionCheckingTrait;
use jasonwynn10\VanillaEntityAI\entity\InventoryHolder;
use jasonwynn10\VanillaEntityAI\entity\ItemHolderTrait;
use jasonwynn10\VanillaEntityAI\entity\Linkable;
use jasonwynn10\VanillaEntityAI\entity\SpawnableTrait;
use pocketmine\block\Water;
use pocketmine\entity\Ageable;
use pocketmine\entity\Effect;
use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\level\biome\Biome;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\EntityEventPacket;
use pocketmine\Player;

class Zombie extends \pocketmine\entity\Zombie implements Ageable, CustomMonster, Collidable, InventoryHolder {
	use ItemHolderTrait, CollisionCheckingTrait, SpawnableTrait;
	public const NETWORK_ID = self::ZOMBIE;
	public $width = 0.6;
	public $height = 1.95;
	/** @var Position|null */
	protected $target;
	/** @var int */
	protected $moveTime;
	/** @var int */
	protected $attackDelay;
	/** @var float $speed */
	protected $speed = 1.2;
	/** @var float $stepHeight */
	protected $stepHeight = 1.0;
	/** @var bool $baby */
	protected $baby = false;

	public function initEntity() : void {
		if(mt_rand(1, 100) < 6)
			$this->setBaby();
		if(mt_rand(1, 100) >= 80) {
			if((bool)mt_rand(0,1))
				$this->equipRandomItems();
			else
				$this->equipRandomArmour();
		}
		parent::initEntity();
	}

	public function onUpdate(int $currentTick) : bool {
		if($this->closed) {
			return false;
		}
		$tickDiff = $currentTick - $this->lastUpdate;
		if($this->target !== null) {
			$x = $this->target->x - $this->x;
			$y = $this->target->y - $this->y;
			$z = $this->target->z - $this->z;
			$diff = abs($x) + abs($z);
			if($diff > 0) {
				$this->motion->x = $this->speed * 0.15 * ($x / $diff);
				$this->motion->z = $this->speed * 0.15 * ($z / $diff);
				$this->yaw = rad2deg(-atan2($x / $diff, $z / $diff));
			}
			$this->pitch = $y == 0 ? 0 : rad2deg(-atan2($y, sqrt($x * $x + $z * $z)));
			if($this->distance($this->target) <= 0 and !$this->target instanceof Entity) {
				$this->target = null;
			}
		}else {
			// TODO: random wandering target
		}
		return parent::onUpdate($currentTick);
	}

	/**
	 * @param int $tickDiff
	 *
	 * @return bool
	 */
	public function entityBaseTick(int $tickDiff = 1) : bool {
		$this->checkNearEntities();
		if($this->target === null) {
			foreach($this->hasSpawned as $player) {
				if($player->isSurvival() and $this->distance($player) <= 16 and $this->hasLineOfSight($player)) {
					$this->target = $player;
				}
			}
		}elseif($this->target instanceof Player){
			if($this->target->isCreative() or !$this->target->isAlive()) {
				$this->target = null;
			}
		}
		$hasUpdate = parent::entityBaseTick($tickDiff);
		if($this->moveTime > 0) {
			$this->moveTime -= $tickDiff;
		}
		$time = $this->getLevel()->getTime() % Level::TIME_FULL;
		if(!$this->isOnFire() and ($time < Level::TIME_NIGHT or $time > Level::TIME_SUNRISE) and $this->level->getBlockSkyLightAt($this->getFloorX(), $this->getFloorY(), $this->getFloorZ()) > 7) {
			$this->setOnFire(2);
		}
		if($this->isOnFire() and $this->level->getBlock($this, true, false) instanceof Water) { // TODO: check weather
			$this->extinguish();
		}
		$this->attackDelay += $tickDiff;
		if(!$this->hasEffect(Effect::WATER_BREATHING) and $this->isUnderwater()) {
			$hasUpdate = true;
			$airTicks = $this->getAirSupplyTicks() - $tickDiff;
			// TODO: drowned transformation
			if($airTicks <= -20) {
				$airTicks = 0;
				$ev = new EntityDamageEvent($this, EntityDamageEvent::CAUSE_DROWNING, 2);
				$this->attack($ev);
			}
			$this->setAirSupplyTicks($airTicks);
		}else {
			$this->setAirSupplyTicks(300);
		}
		return $hasUpdate;
	}

	/**
	 * @return array
	 */
	public function getDrops() : array {
		$drops = parent::getDrops();
		if($this->dropAll) {
			$drops = array_merge($drops, $this->armorInventory->getContents());
		}elseif(mt_rand(1, 100) <= 8.5) {
			if(!empty($this->armorInventory->getContents())) {
				$drops[] = $this->armorInventory->getContents()[array_rand($this->armorInventory->getContents())];
			}
		}
		return $drops;
	}

	/**
	 * @return int
	 */
	public function getXpDropAmount() : int {
		if($this->baby) {
			$exp = 12;
		}else{
			$exp = 5;
		}
		foreach($this->getArmorInventory()->getContents() as $piece)
			$exp += mt_rand(1, 3);
		return $exp;
	}

	/**
	 * @return bool
	 */
	public function isBaby() : bool {
		return $this->baby;
	}

	/**
	 * @return string
	 */
	public function getName() : string {
		return "Zombie";
	}

	/**
	 * @param Player $player
	 */
	public function onCollideWithPlayer(Player $player) : void {
		parent::onCollideWithPlayer($player);
		if($this->target === $player) {
			$damage = 2;
			switch($this->getLevel()->getDifficulty()) {
				case Level::DIFFICULTY_EASY:
					$damage = 2;
				break;
				case Level::DIFFICULTY_NORMAL:
					$damage = 3;
				break;
				case Level::DIFFICULTY_HARD:
					$damage = 4;
			}
			// TODO: add damage from items in hand
			$pk = new EntityEventPacket();
			$pk->entityRuntimeId = $this->id;
			$pk->event = EntityEventPacket::ARM_SWING;
			$this->server->broadcastPacket($this->hasSpawned, $pk);
			$player->attack(new EntityDamageByEntityEvent($this, $player, EntityDamageByEntityEvent::CAUSE_ENTITY_ATTACK, $damage));
		}
	}

	/**
	 * @return null|Position|Entity
	 */
	public function getTarget() : ?Position {
		return $this->target;
	}

	/**
	 * @param Entity $entity
	 */
	public function onCollideWithEntity(Entity $entity) : void {
		if($this->target === $entity) {
			$damage = 2;
			switch($this->getLevel()->getDifficulty()) {
				case Level::DIFFICULTY_EASY:
					$damage = 2;
				break;
				case Level::DIFFICULTY_NORMAL:
					$damage = 3;
				break;
				case Level::DIFFICULTY_HARD:
					$damage = 4;
			}
			$entity->attack(new EntityDamageByEntityEvent($this, $entity, EntityDamageByEntityEvent::CAUSE_ENTITY_ATTACK, $damage));
		}
	}

	/**
	 * @param Position $spawnPos
	 * @param CompoundTag|null $spawnData
	 *
	 * @return null|Living
	 */
	public static function spawnMob(Position $spawnPos, ?CompoundTag $spawnData = null) : ?Living {
		$nbt = self::createBaseNBT($spawnPos);
		if(isset($spawnData)) {
			$nbt = $spawnData->merge($nbt);
			$nbt->setInt("id", self::NETWORK_ID);
		}
		if($spawnPos->level->getBiomeId($spawnPos->x, $spawnPos->z) === Biome::DESERT and mt_rand(1, 100) > 80) {
			/** @var Husk $entity */
			$entity = self::createEntity(Husk::NETWORK_ID, $spawnPos->level, $nbt);
		}else{
			/** @var self $entity */
			$entity = self::createEntity(self::NETWORK_ID, $spawnPos->level, $nbt);
		}
		// TODO: work on logic here more
		if(!$spawnPos->isValid() or !$entity->onGround or $spawnPos->level->getFullLight($spawnPos) > $entity->spawnLight) {
			$entity->flagForDespawn();
			return null;
		}else{
			$entity->spawnToAll();
			return $entity;
		}
	}

	/**
	 * @return Linkable|null
	 */
	public function getLink() : ?Linkable {
		// TODO: Implement getLink() method.
	}

	/**
	 * @param Linkable $entity
	 *
	 * @return Zombie
	 */
	public function setLink(Linkable $entity) {
		// TODO: Implement setLink() method.
		return $this;
	}

	/**
	 * @param bool $baby
	 *
	 * @return Zombie
	 */
	public function setBaby(bool $baby = true) : self {
		$this->baby = $baby;
		$this->setDataFlag(self::DATA_FLAGS, self::DATA_FLAG_BABY, $baby);
		$this->setSprinting();
		$this->setScale(0.5);
		return $this;
	}

	/**
	 * @param float $speed
	 *
	 * @return Zombie
	 */
	public function setSpeed(float $speed) : self {
		$this->speed = $speed;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getSpeed() : float {
		return $this->speed;
	}

	public function equipRandomItems() : void {
		//TODO random enchantments and random item (iron sword or iron shovel or iron axe)
	}

	public function equipRandomArmour() : void {
		//TODO random enchantments and random armour
	}
}