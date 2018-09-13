<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\inventory;

use pocketmine\entity\Monster;
use pocketmine\inventory\BaseInventory;
use pocketmine\item\Armor;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\item\TieredTool;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\network\mcpe\protocol\types\ContainerIds;
use pocketmine\Player;

class MobInventory extends BaseInventory {
	protected $maxStackSize = 1;
	/** @var Monster */
	protected $holder;
	/** @var int */
	protected $itemInHandIndex = 0;

	/**
	 * MobInventory constructor.
	 *
	 * @param Monster $monster
	 * @param Item $item
	 */
	public function __construct(Monster $monster, Item $item) {
		$this->holder = $monster;
		parent::__construct([$item]);
	}

	public function getName(): string {
		return "Mob";
	}

	public function getDefaultSize(): int {
		return 1;
	}

	/**
	 * @param Item ...$slots
	 *
	 * @return array
	 */
	public function addItem(Item ...$slots): array {
		foreach($slots as $slot) {
			if($slot instanceof Armor or $slot->getId() === ItemIds::PUMPKIN or $slot->getId() === ItemIds::LIT_PUMPKIN or $slot->getId() === ItemIds::SKULL) {
				//
			}elseif($slot instanceof TieredTool) {
				//
			}
		}
		return [];
	}

	/**
	 * Called when a client equips a hotbar slot. This method should not be used by plugins.
	 * This method will call PlayerItemHeldEvent.
	 *
	 * @param int $hotbarSlot Number of the hotbar slot to equip.
	 *
	 * @return bool if the equipment change was successful, false if not.
	 */
	public function equipItem(int $hotbarSlot): bool {
		$this->setHeldItemIndex($hotbarSlot);
		return true;
	}

	/**
	 * Sets which hotbar slot the player is currently loading.
	 *
	 * @param int $hotbarSlot 0-8 index of the hotbar slot to hold
	 */
	public function setHeldItemIndex(int $hotbarSlot) {
		$this->throwIfNotHotbarSlot($hotbarSlot);
		$this->itemInHandIndex = $hotbarSlot;
		$this->sendHeldItem($this->getHolder()->getViewers());
	}

	/**
	 * @param int $slot
	 *
	 * @throws \InvalidArgumentException
	 */
	private function throwIfNotHotbarSlot(int $slot) {
		if(!$this->isHotbarSlot($slot)) {
			throw new \InvalidArgumentException("$slot is not a valid hotbar slot index (expected 0 - " . ($this->getHotbarSize() - 1) . ")");
		}
	}

	private function isHotbarSlot(int $slot): bool {
		return $slot >= 0 and $slot <= $this->getHotbarSize();
	}

	/**
	 * Returns the number of slots in the hotbar.
	 * @return int
	 */
	public function getHotbarSize(): int {
		return 1;
	}

	/**
	 * Sends the currently-held item to specified targets.
	 *
	 * @param Player[] $target
	 */
	public function sendHeldItem(array $target) {
		$item = $this->getItemInHand();
		$pk = new MobEquipmentPacket();
		$pk->entityRuntimeId = $this->holder->getId();
		$pk->item = $item;
		$pk->inventorySlot = $pk->hotbarSlot = $this->itemInHandIndex;
		$pk->windowId = ContainerIds::INVENTORY;
		$this->holder->getLevel()->getServer()->broadcastPacket($target, $pk);
	}

	/**
	 * Returns the currently-held item.
	 *
	 * @return Item
	 */
	public function getItemInHand(): Item {
		return $this->getHotbarSlotItem($this->itemInHandIndex);
	}

	/**
	 * Returns the item in the specified hotbar slot.
	 *
	 * @param int $hotbarSlot
	 *
	 * @return Item
	 *
	 * @throws \InvalidArgumentException if the hotbar slot index is out of range
	 */
	public function getHotbarSlotItem(int $hotbarSlot): Item {
		$this->throwIfNotHotbarSlot($hotbarSlot);
		return $this->getItem($hotbarSlot);
	}

	/**
	 * This override is here for documentation and code completion purposes only.
	 * @return Monster
	 */
	public function getHolder() {
		return $this->holder;
	}

	/**
	 * Returns the hotbar slot number the holder is currently holding.
	 * @return int
	 */
	public function getHeldItemIndex(): int {
		return $this->itemInHandIndex;
	}

	/**
	 * Sets the item in the currently-held slot to the specified item.
	 *
	 * @param Item $item
	 *
	 * @return bool
	 */
	public function setItemInHand(Item $item): bool {
		return $this->setItem($this->itemInHandIndex, $item);
	}
}