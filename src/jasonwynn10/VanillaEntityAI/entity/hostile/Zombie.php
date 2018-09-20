<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\hostile;

use jasonwynn10\VanillaEntityAI\entity\Collidable;
use jasonwynn10\VanillaEntityAI\entity\InventoryHolder;
use jasonwynn10\VanillaEntityAI\entity\Linkable;
use jasonwynn10\VanillaEntityAI\inventory\MobInventory;
use pocketmine\block\Water;
use pocketmine\entity\Ageable;
use pocketmine\entity\Effect;
use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\AxisAlignedBB;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\EntityEventPacket;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\Player;

class Zombie extends \pocketmine\entity\Zombie implements Ageable, CustomMonster, InventoryHolder, Collidable {
	public const NETWORK_ID = self::ZOMBIE;
	public $width = 0.6;
	public $height = 1.8;
	/** @var MobInventory */
	protected $inventory;
	/** @var Position|null */
	protected $target;
	/** @var int */
	protected $moveTime;
	/** @var int */
	protected $attackDelay;
	/** @var bool */
	protected $dropAll = false;

	public function initEntity(): void {
		$this->inventory = new MobInventory($this, ItemFactory::get(Item::AIR)); //TODO random enchantments and random item (iron sword or iron shovel or iron axe)
		parent::initEntity();
	}

	/**
	 * @param int $tickDiff
	 *
	 * @return bool
	 */
	public function entityBaseTick(int $tickDiff = 1): bool {
		$this->checkNearEntities();
		$hasUpdate = parent::entityBaseTick($tickDiff);
		if($this->moveTime > 0) {
			$this->moveTime -= $tickDiff;
		}
		$time = $this->getLevel()->getTime() % Level::TIME_FULL;
		if(!$this->isOnFire() and ($time < Level::TIME_NIGHT or $time > Level::TIME_SUNRISE)) {
			$this->setOnFire(100);
		}
		if($this->isOnFire() and $this->level->getBlock($this, true, false) instanceof Water) {
			$this->extinguish();
		}
		$this->attackDelay += $tickDiff;
		if(!$this->hasEffect(Effect::WATER_BREATHING) and $this->isUnderwater()) {
			$hasUpdate = true;
			$airTicks = $this->getAirSupplyTicks() - $tickDiff;
			// TODO : drowned transformation
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

	protected function checkNearEntities() {
		foreach($this->level->getNearbyEntities($this->boundingBox->expandedCopy(1, 0.5, 1), $this) as $entity) {
			$entity->scheduleUpdate();
			if(!$entity->isAlive() or $entity->isFlaggedForDespawn()) {
				continue;
			}
			if($entity instanceof Collidable) {
				$entity->onCollideWithEntity($this);
			}
		}
	}

	/**
	 * @return array
	 */
	public function getDrops(): array {
		$drops = parent::getDrops();
		if($this->dropAll) {
			$drops = array_merge($drops, $this->inventory->getContents(), $this->armorInventory->getContents());
		}elseif(mt_rand(1, 100) <= 8.5) {
			if(!empty($this->inventory->getContents()) and !empty($this->armorInventory->getContents())) {
				if((bool)mt_rand(0, 1)) {
					$drops[] = $this->inventory->getContents()[array_rand($this->inventory->getContents())];
				}else {
					$drops[] = $this->armorInventory->getContents()[array_rand($this->armorInventory->getContents())];
				}
			}elseif(empty($this->inventory->getContents()) and !empty($this->armorInventory->getContents())) {
				$drops[] = $this->armorInventory->getContents()[array_rand($this->armorInventory->getContents())];
			}elseif(empty($this->armorInventory->getContents()) and !empty($this->inventory->getContents())) {
				$drops[] = $this->inventory->getContents()[array_rand($this->inventory->getContents())];
			}
		}
		return $drops;
	}

	/**
	 * @return int
	 */
	public function getXpDropAmount(): int {
		if($this->isBaby()) {
			return 12;
		}
		$exp = 5;
		foreach($this->getArmorInventory()->getContents() as $piece)
			$exp += mt_rand(1, 3);
		return $exp;
	}

	public function isBaby(): bool {
		return false; //TODO
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return "Zombie";
	}

	public function close(): void {
		if(!$this->closed) {
			if($this->inventory !== null) {
				$this->inventory->removeAllViewers(true);
				$this->inventory = null;
			}
			parent::close();
		}
	}

	public function onCollideWithPlayer(Player $player): void {
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
			$pk = new EntityEventPacket();
			$pk->entityRuntimeId = $this->id;
			$pk->event = EntityEventPacket::ARM_SWING;
			$this->server->broadcastPacket($this->hasSpawned, $pk);
			$player->attack(new EntityDamageByEntityEvent($this, $player, EntityDamageByEntityEvent::CAUSE_ENTITY_ATTACK, $damage));
		}
	}

	protected function sendSpawnPacket(Player $player): void {
		parent::sendSpawnPacket($player);
		$pk = new MobEquipmentPacket();
		$pk->entityRuntimeId = $this->getId();
		$pk->item = $this->inventory->getItemInHand();
		$pk->inventorySlot = $pk->hotbarSlot = $this->inventory->getHeldItemIndex();
		$player->dataPacket($pk);
	}

	/**
	 * @return MobInventory
	 */
	public function getInventory() {
		return $this->inventory;
	}

	/**
	 * @return bool
	 */
	public function isDropAll(): bool {
		return $this->dropAll;
	}

	/**
	 * @param bool $dropAll
	 */
	public function setDropAll(bool $dropAll = true) : void {
		$this->dropAll = $dropAll;
	}

	public function getTarget(): ?Position {
		return $this->target;
	}

	public function onCollideWithEntity(Entity $entity): void {
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
		$width = 0.6;
		$height = 1.8;
		$boundingBox = new AxisAlignedBB(0, 0, 0, 0, 0, 0);
		$halfWidth = $width / 2;
		$boundingBox->setBounds($spawnPos->x - $halfWidth, $spawnPos->y, $spawnPos->z - $halfWidth, $spawnPos->x + $halfWidth, $spawnPos->y + $height, $spawnPos->z + $halfWidth);
		// TODO: work on logic here more
		if($spawnPos->level === null or !empty($spawnPos->level->getCollisionBlocks($boundingBox, true)) or !$spawnPos->level->getBlock($spawnPos->subtract(0, 1), true, false)->isSolid()) {
			return null;
		}
		$nbt = self::createBaseNBT($spawnPos);
		if(isset($spawnData)) {
			$nbt = $spawnData->merge($nbt);
			$nbt->setInt("id", self::NETWORK_ID);
		}else {
			// TODO: randomized gear and other
		}
		/** @var self $entity */
		$entity = self::createEntity("Zombie", $spawnPos->level, $nbt);
		return $entity;
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
}