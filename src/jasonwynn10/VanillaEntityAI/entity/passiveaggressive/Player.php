<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\passiveaggressive;

use jasonwynn10\VanillaEntityAI\entity\CreatureBase;
use jasonwynn10\VanillaEntityAI\entity\hostile\Creeper;
use jasonwynn10\VanillaEntityAI\entity\hostile\Enderman;
use jasonwynn10\VanillaEntityAI\entity\Interactable;
use jasonwynn10\VanillaEntityAI\network\PlayerNetworkSessionAdapter;
use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\InteractPacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\PlayerInputPacket;
use pocketmine\network\SourceInterface;

class Player extends \pocketmine\Player {
	/**
	 * @param SourceInterface $interface
	 * @param string $ip
	 * @param int $port
	 */
	public function __construct(SourceInterface $interface, string $ip, int $port) {
		parent::__construct($interface, $ip, $port);
		$this->sessionAdapter = new PlayerNetworkSessionAdapter($this->server, $this);
	}

	/**
	 * @param PlayerInputPacket $packet
	 *
	 * @return bool
	 */
	public function handlePlayerInput(PlayerInputPacket $packet) : bool {
		return false;
	}

	public function handleInteract(InteractPacket $packet) : bool {
		$return = parent::handleInteract($packet);
		switch($packet->action) {
			case InteractPacket::ACTION_LEAVE_VEHICLE:
				// TODO: entity linking
				break;
			case InteractPacket::ACTION_MOUSEOVER:
				$target = $this->level->getEntity($packet->target);
				$this->setTargetEntity($target);
				if($target instanceof CreatureBase){
					// TODO: check player looking at head and if wearing jack 'o lantern
					$target->onPlayerLook($this);
				}
				$return = true;
				break;
			default:
				$this->server->getLogger()->debug("Unhandled/unknown interaction type " . $packet->action . "received from " . $this->getName());
				$return = false;
		}
		return $return;
	}

	public function handleInventoryTransaction(InventoryTransactionPacket $packet) : bool {
		$return = parent::handleInventoryTransaction($packet);
		if($packet->transactionType === InventoryTransactionPacket::TYPE_USE_ITEM_ON_ENTITY) {
			$type = $packet->trData->actionType;
			switch($type) {
				case InventoryTransactionPacket::USE_ITEM_ON_ENTITY_ACTION_INTERACT:
					$target = $this->level->getEntity($packet->trData->entityRuntimeId);
					$this->setTargetEntity($target);
					$this->getDataPropertyManager()->setString(Entity::DATA_INTERACTIVE_TAG, ""); // Don't show button anymore
					if($target instanceof Interactable) {
						$target->onPlayerInteract($this);
						$return = true;
						break;
					}
					$return = false;
			}
		}
		return $return;
	}
}