<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\hostile;

use jasonwynn10\VanillaEntityAI\entity\InventoryHolder;
use jasonwynn10\VanillaEntityAI\entity\Linkable;
use jasonwynn10\VanillaEntityAI\inventory\MobInventory;
use pocketmine\entity\Living;
use pocketmine\entity\Monster;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\level\Position;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\Player;

class Skeleton extends Monster implements CustomMonster, InventoryHolder {
	public const NETWORK_ID = self::SKELETON;
	public $width = 0.875;
	public $height = 2.0;
	/** @var MobInventory */
	protected $inventory;
	/** @var Position|null */
	protected $target;
	/** @var bool */
	protected $dropAll = false;

	public function initEntity(): void {
		$this->inventory = new MobInventory($this, ItemFactory::get(Item::BOW)); //TODO random enchantments
		parent::initEntity();
	}

	/**
	 * @param int $tickDiff
	 *
	 * @return bool
	 */
	public function entityBaseTick(int $tickDiff = 1): bool {
		return parent::entityBaseTick($tickDiff); // TODO: Change the autogenerated stub
	}

	/**
	 * @return array
	 */
	public function getDrops(): array {
		$drops = parent::getDrops();
		//TODO chance drop item and armour
		return $drops;
	}

	/**
	 * @return int
	 */
	public function getXpDropAmount(): int {
		//TODO: check for equipment and whether it's a baby
		return 5;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return "Skeleton";
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
	public function setDropAll(bool $dropAll = true): void {
		$this->dropAll = $dropAll;
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
}