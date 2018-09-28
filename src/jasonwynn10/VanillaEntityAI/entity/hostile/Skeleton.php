<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\hostile;

use jasonwynn10\VanillaEntityAI\entity\Collidable;
use jasonwynn10\VanillaEntityAI\entity\CollisionCheckingTrait;
use jasonwynn10\VanillaEntityAI\entity\InventoryHolder;
use jasonwynn10\VanillaEntityAI\entity\ItemHolderTrait;
use jasonwynn10\VanillaEntityAI\entity\Linkable;
use pocketmine\block\Water;
use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\entity\Monster;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;

class Skeleton extends Monster implements CustomMonster, InventoryHolder, Collidable {
	use ItemHolderTrait, CollisionCheckingTrait;
	public const NETWORK_ID = self::SKELETON;
	public $width = 0.875;
	public $height = 2.0;
	/** @var Position|null */
	protected $target;
	/** @var int */
	protected $moveTime;
	/** @var int */
	protected $attackDelay;
	/** @var float $speed */
	protected $speed = 1.0;
	/** @var float $stepHeight */
	protected $stepHeight = 1.0;

	public function initEntity(): void {
		if(!isset($this->mainHand))
			$this->mainHand = Item::get(Item::BOW); // TODO: random enchantments
		// TODO: random armour
		parent::initEntity();
	}

	/**
	 * @param int $tickDiff
	 *
	 * @return bool
	 */
	public function entityBaseTick(int $tickDiff = 1): bool {
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
	public function getXpDropAmount(): int {
		$exp = 5;
		foreach($this->getArmorInventory()->getContents() as $piece)
			$exp += mt_rand(1, 3);
		return $exp;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return "Skeleton";
	}

	/**
	 * @return Position|null
	 */
	public function getTarget(): ?Position {
		return $this->target;
	}

	/**
	 * @param Position $spawnPos
	 * @param CompoundTag|null $spawnData
	 *
	 * @return null|Living
	 */
	public static function spawnMob(Position $spawnPos, ?CompoundTag $spawnData = null) : ?Living {
		// TODO: Implement spawnMob() method.
	}

	/**
	 * @return Linkable|null
	 */
	public function getLink() : ?Linkable {
		// TODO: Implement getLink() method.
	}

	/**
	 * @param Linkable $entity
	 */
	public function setLink(Linkable $entity) {
		// TODO: Implement setLink() method.
	}

	/**
	 * @return float
	 */
	public function getSpeed(): float {
		return $this->speed;
	}

	/**
	 * @param float $speed
	 *
	 * @return self
	 */
	public function setSpeed(float $speed) {
		$this->speed = $speed;
		return $this;
	}

	/**
	 * @param Position $spawnPos
	 * @param null|CompoundTag $spawnData
	 *
	 * @return null|self
	 */
	public static function spawnFromSpawner(Position $spawnPos, ?CompoundTag $spawnData = null) {
		// TODO: Implement spawnFromSpawner() method.
	}

	public function equipRandomItems(): void {
		// TODO: Implement equipRandomItems() method.
	}

	public function equipRandomArmour(): void {
		// TODO: Implement equipRandomArmour() method.
	}

	public function onCollideWithEntity(Entity $entity): void {
		// TODO: Implement onCollideWithEntity() method.
	}
}