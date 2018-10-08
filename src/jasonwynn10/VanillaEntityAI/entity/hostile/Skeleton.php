<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\hostile;

use jasonwynn10\VanillaEntityAI\entity\ClimbingTrait;
use jasonwynn10\VanillaEntityAI\entity\CreatureBase;
use jasonwynn10\VanillaEntityAI\entity\InventoryHolder;
use jasonwynn10\VanillaEntityAI\entity\ItemHolderTrait;
use jasonwynn10\VanillaEntityAI\entity\MonsterBase;
use jasonwynn10\VanillaEntityAI\entity\neutral\Arrow;
use jasonwynn10\VanillaEntityAI\EntityAI;
use pocketmine\block\Water;
use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Projectile;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\level\sound\LaunchSound;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\TakeItemEntityPacket;
use pocketmine\Player;

class Skeleton extends MonsterBase implements InventoryHolder {
	use ItemHolderTrait, ClimbingTrait;
	public const NETWORK_ID = self::SKELETON;
	public $width = 0.875;
	public $height = 2.0;
	/** @var int */
	protected $moveTime;
	/** @var int */
	protected $attackDelay;
	/** @var float $speed */
	protected $speed = 1.0;

	public function initEntity() : void {
		if(!isset($this->mainHand)) {
			$this->mainHand = Item::get(Item::BOW);
		} // TODO: random enchantments
		// TODO: random armour
		parent::initEntity();
	}

	public function onUpdate(int $currentTick) : bool {
		if($this->isFlaggedForDespawn() or $this->closed) {
			return false;
		}
		if($this->attackTime > 0) {
			return parent::onUpdate($currentTick);
		}else {
			if($this->moveTime <= 0 and $this->isTargetValid($this->target) and !$this->target instanceof Entity) {
				$x = $this->target->x - $this->x;
				$y = $this->target->y - $this->y;
				$z = $this->target->z - $this->z;
				$diff = abs($x) + abs($z);
				if($diff > 0) {
					$this->motion->x = $this->speed * 0.15 * ($x / $diff);
					$this->motion->z = $this->speed * 0.15 * ($z / $diff);
					$this->yaw = rad2deg(-atan2($x / $diff, $z / $diff)); // TODO: desync head with body when AI improves
				}
				$this->pitch = $y == 0 ? 0 : rad2deg(-atan2($y, sqrt($x * $x + $z * $z)));
				if($this->distance($this->target) <= 0) {
					$this->target = null;
				}
			}elseif($this->target instanceof Entity and $this->isTargetValid($this->target)) {
				$this->moveTime = 0;
				if($this->distance($this->target) <= 16) {
					if($this->attackDelay > 30 and mt_rand(1, 32) < 4) {
						$this->attackDelay = 0;
						$force = 1.2; // TODO: correct speed?
						$yaw = $this->yaw + mt_rand(-220, 220) / 10;
						$pitch = $this->pitch + mt_rand(-120, 120) / 10;
						$nbt = Arrow::createBaseNBT(new Vector3($this->x + (-sin($yaw / 180 * M_PI) * cos($pitch / 180 * M_PI) * 0.5), $this->y + $this->eyeHeight, $this->z + (cos($yaw / 180 * M_PI) * cos($pitch / 180 * M_PI) * 0.5)), new Vector3(), $yaw, $pitch);
						/** @var Arrow $arrow */
						$arrow = Arrow::createEntity("Arrow", $this->level, $nbt, $this);
						$this->server->getPluginManager()->callEvent($ev = new EntityShootBowEvent($this, Item::get(Item::ARROW, 0, 1), $arrow, $force));
						$projectile = $ev->getProjectile();
						if($ev->isCancelled()) {
							$projectile->flagForDespawn();
						}elseif($projectile instanceof Projectile) {
							$this->server->getPluginManager()->callEvent($launch = new ProjectileLaunchEvent($projectile));
							if($launch->isCancelled()) {
								$projectile->flagForDespawn();
							}else {
								$projectile->setMotion(new Vector3(-sin($yaw / 180 * M_PI) * cos($pitch / 180 * M_PI) * $ev->getForce(), -sin($pitch / 180 * M_PI) * $ev->getForce(), cos($yaw / 180 * M_PI) * cos($pitch / 180 * M_PI) * $ev->getForce()));
								$projectile->spawnToAll();
								$this->level->addSound(new LaunchSound($this), $projectile->getViewers());
							}
						}
					}
					$target = $this->getSide(self::getRightSide($this->getDirection()));
					$x = $target->x - $this->x;
					$z = $target->z - $this->z;
					$diff = abs($x) + abs($z);
					if($diff > 0) {
						$this->motion->x = $this->speed * 0.15 * ($x / $diff);
						$this->motion->z = $this->speed * 0.15 * ($z / $diff);
					}
					$this->lookAt($this->target->add(0, $this->target->eyeHeight));
				}else {
					$x = $this->target->x - $this->x;
					$y = $this->target->y - $this->y;
					$z = $this->target->z - $this->z;
					$diff = abs($x) + abs($z);
					if($diff > 0) {
						$this->motion->x = $this->speed * 0.15 * ($x / $diff);
						$this->motion->z = $this->speed * 0.15 * ($z / $diff);
						$this->yaw = rad2deg(-atan2($x / $diff, $z / $diff)); // TODO: desync head with body when AI improves
					}
					$this->pitch = $y == 0 ? 0 : rad2deg(-atan2($y, sqrt($x * $x + $z * $z)));
				}
			}elseif($this->moveTime <= 0) {
				$this->moveTime = 100;
				// TODO: random target position
			}
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
		}elseif($this->target instanceof Player) {
			if($this->target->isCreative() or !$this->target->isAlive()) {
				$this->target = null;
			}
		}
		$hasUpdate = parent::entityBaseTick($tickDiff);
		if($this->moveTime > 0) {
			$this->moveTime -= $tickDiff;
		}
		$time = $this->getLevel()->getTime() % Level::TIME_FULL;
		if(!$this->isOnFire() and ($time < Level::TIME_NIGHT or $time > Level::TIME_SUNRISE) and $this->level->getBlockSkyLightAt($this->getFloorX(), $this->getFloorY(), $this->getFloorZ()) >= 15) {
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
	public function getXpDropAmount() : int {
		$exp = 5;
		foreach($this->getArmorInventory()->getContents() as $piece)
			$exp += mt_rand(1, 3);
		return $exp;
	}

	/**
	 * @return string
	 */
	public function getName() : string {
		return "Skeleton";
	}

	/**
	 * @param Position $spawnPos
	 * @param CompoundTag|null $spawnData
	 *
	 * @return null|CreatureBase
	 */
	public static function spawnMob(Position $spawnPos, ?CompoundTag $spawnData = null) : ?CreatureBase {
		// TODO: Implement spawnMob() method.
	}

	/**
	 * @param Position $spawnPos
	 * @param null|CompoundTag $spawnData
	 *
	 * @return null|self
	 */
	public static function spawnFromSpawner(Position $spawnPos, ?CompoundTag $spawnData = null) : ?CreatureBase {
		// TODO: Implement spawnFromSpawner() method.
	}

	public function onCollideWithEntity(Entity $entity) : void {
		// TODO: Implement onCollideWithEntity() method.
		if($entity instanceof \jasonwynn10\VanillaEntityAI\entity\neutral\Item) {
			if($entity->getPickupDelay() > 0 or !$this instanceof InventoryHolder or $this->level->getDifficulty() <= Level::DIFFICULTY_EASY) {
				return;
			}
			$chance = EntityAI::getInstance()->getRegionalDifficulty($this->level, $this->chunk);
			if($chance < 50) {
				return;
			}
			$item = $entity->getItem();
			if(!$this->checkItemValueToMainHand($item) and !$this->checkItemValueToOffHand($item)) {
				return;
			}
			$pk = new TakeItemEntityPacket();
			$pk->eid = $this->getId();
			$pk->target = $this->getId();
			$this->server->broadcastPacket($this->getViewers(), $pk);
			$this->setDropAll();
			$this->setPersistence(true);
			if($this->checkItemValueToMainHand($item)) {
				$this->mainHand = clone $item;
			}elseif($this->checkItemValueToOffHand($item)) {
				$this->offHand = clone $item;
			}
		}
	}

	/**
	 * @param Item $item
	 *
	 * @return bool
	 */
	public function checkItemValueToMainHand(Item $item) : bool {
		return $this->mainHand === null;
	}

	/**
	 * @param Item $item
	 *
	 * @return bool
	 */
	public function checkItemValueToOffHand(Item $item) : bool {
		return false;
	}

	public function equipRandomItems() : void {
	}

	public function equipRandomArmour() : void {
		// TODO: random armour chance by difficulty
	}
}