<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\passiveaggressive;

use jasonwynn10\VanillaEntityAI\entity\CreatureBase;
use jasonwynn10\VanillaEntityAI\entity\hostile\Creeper;
use jasonwynn10\VanillaEntityAI\network\PlayerNetworkSessionAdapter;
use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\InteractPacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\PlayerInputPacket;
use pocketmine\network\SourceInterface;

class Player extends \pocketmine\Player {
	/** @var Entity|null $lookingAt */
	private $lookingAt;

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
	public function handlePlayerInput(PlayerInputPacket $packet): bool {
		return false;
	}

	public function handleInteract(InteractPacket $packet) : bool {
		$return = parent::handleInteract($packet);
		switch($packet->action) {
			case InteractPacket::ACTION_LEAVE_VEHICLE:
				// TODO:
				break;
			case InteractPacket::ACTION_MOUSEOVER:
				$this->lookingAt = $this->level->getEntity($packet->target);
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
					{
						$target = $this->level->getEntity($packet->trData->entityRuntimeId);
						$this->lookingAt = $target;
						if($target instanceof CreatureBase) {
							$this->getDataPropertyManager()->setString(Entity::DATA_INTERACTIVE_TAG, ""); // Don't show button anymore
							if($target instanceof Creeper) {
								$target->ignite();
							}
							$return = true;
							break;
						}
						$return = false;
					}
			}
		}
		return $return;
	}

	/**
	 * @return Entity|null
	 */
	public function getLookingAt() : ?Entity {
		return $this->lookingAt;
	}

	/**
	 * @param Entity|null $lookingAt
	 *
	 * @return Player
	 */
	public function setLookingAt(?Entity $lookingAt) : self {
		$this->lookingAt = $lookingAt;
		return $this;
	}
}