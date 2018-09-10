<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\hostile;

use jasonwynn10\VanillaEntityAI\entity\InventoryHolder;
use jasonwynn10\VanillaEntityAI\inventory\MobInventory;
use jasonwynn10\VanillaEntityAI\entity\Collidable;
use pocketmine\block\Water;
use pocketmine\entity\Ageable;
use pocketmine\entity\Effect;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\Player;

class Zombie extends \pocketmine\entity\Zombie implements Ageable, CustomMonster, InventoryHolder, Collidable {
	public const NETWORK_ID = self::ZOMBIE;

	public $width = 0.6;
	public $height = 1.8;

	/** @var MobInventory  */
	protected $inventory;
	/** @var Position|null  */
	protected $target;
	/** @var int  */
	protected $moveTime;
	/** @var int  */
	protected $attackDelay;
	/** @var bool  */
	protected $dropAll = false;

	public function initEntity() : void {
		$this->inventory = new MobInventory($this, ItemFactory::get(Item::AIR)); //TODO random enchantments and random item (iron sword or iron shovel or iron axe)
		parent::initEntity();
	}

	/**
	 * @param int $tickDiff
	 *
	 * @return bool
	 */
	public function entityBaseTick(int $tickDiff = 1) : bool {

		$this->checkNearEntities();

		$hasUpdate = parent::entityBaseTick($tickDiff);

		if($this->moveTime > 0) {
			$this->moveTime -= $tickDiff;
		}

		$time = $this->getLevel()->getTime() % Level::TIME_FULL;
		if(!$this->isOnFire() and ($time < Level::TIME_NIGHT or $time > Level::TIME_SUNRISE))
			$this->setOnFire(100);

		if($this->isOnFire() and $this->level->getBlock($this, true, false) instanceof Water) {
			$this->extinguish();
		}

		$this->attackDelay += $tickDiff;

		if(!$this->hasEffect(Effect::WATER_BREATHING) and $this->isUnderwater()){
			$hasUpdate = true;
			$airTicks = $this->getAirSupplyTicks() - $tickDiff;
			// TODO : drowned transformation
			if($airTicks <= -20) {
				$airTicks = 0;
				$ev = new EntityDamageEvent($this, EntityDamageEvent::CAUSE_DROWNING, 2);
				$this->attack($ev);
			}
			$this->setAirSupplyTicks($airTicks);
		}else{
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
			$drops = array_merge($drops, $this->inventory->getContents(), $this->armorInventory->getContents());
		}elseif(mt_rand(1,100) <= 8.5) {
			if(!empty($this->inventory->getContents()) and !empty($this->armorInventory->getContents())) {
				if((bool)mt_rand(0,1)) {
					$drops[] = $this->inventory->getContents()[array_rand($this->inventory->getContents())];
				}else{
					$drops[] = $this->armorInventory->getContents()[array_rand($this->armorInventory->getContents())];
				}
			}elseif(empty($this->inventory->getContents())) {
				$drops[] = $this->armorInventory->getContents()[array_rand($this->armorInventory->getContents())];
			}else{
				$drops[] = $this->inventory->getContents()[array_rand($this->inventory->getContents())];
			}
		}
		return $drops;
	}

	/**
	 * @return int
	 */
	public function getXpDropAmount() : int {
		if($this->isBaby())
			return 12;
		$exp = 5;
		foreach($this->getArmorInventory()->getContents() as $piece)
			$exp += mt_rand(1, 3);
		return $exp;
	}

	public function isBaby() : bool {
		return false; //TODO
	}

	/**
	 * @return string
	 */
	public function getName() : string {
		return "Zombie";
	}

	/**
	 * @return MobInventory
	 */
	public function getInventory() {
		return $this->inventory;
	}

	public function close() : void {
		if(!$this->closed) {
			if($this->inventory !== null) {
				$this->inventory->removeAllViewers(true);
				$this->inventory = null;
			}
			parent::close();
		}
	}

	protected function sendSpawnPacket(Player $player) : void {
		parent::sendSpawnPacket($player);

		$pk = new MobEquipmentPacket();
		$pk->entityRuntimeId = $this->getId();
		$pk->item = $this->inventory->getItemInHand();
		$pk->inventorySlot = $pk->hotbarSlot = $this->inventory->getHeldItemIndex();
		$player->dataPacket($pk);
	}

	public function getTarget() : ?Position {
		return $this->target;
	}

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
			$player->attack(new EntityDamageByEntityEvent($this, $player, EntityDamageByEntityEvent::CAUSE_ENTITY_ATTACK, $damage));
		}
	}

	protected function checkNearEntities() {
		foreach($this->level->getNearbyEntities($this->boundingBox->expandedCopy(1, 0.5, 1), $this) as $entity) {
			$entity->scheduleUpdate();

			if(!$entity->isAlive() or $entity->isFlaggedForDespawn()){
				continue;
			}

			if($entity instanceof Collidable)
				$entity->onCollideWithEntity($this);
		}
	}

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
	 * @return bool
	 */
	public function isDropAll() : bool {
		return $this->dropAll;
	}

	/**
	 * @param bool $dropAll
	 */
	public function setDropAll(bool $dropAll) : void {
		$this->dropAll = $dropAll;
	}
}