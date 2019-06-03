<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\passive;

use jasonwynn10\VanillaEntityAI\entity\AnimalBase;
use jasonwynn10\VanillaEntityAI\entity\Collidable;
use jasonwynn10\VanillaEntityAI\entity\Interactable;
use jasonwynn10\VanillaEntityAI\entity\passiveaggressive\Player;
use jasonwynn10\VanillaEntityAI\item\Saddle;
use pocketmine\entity\Entity;
use pocketmine\entity\Rideable;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

class Pig extends AnimalBase implements Collidable, Interactable, Rideable {
	public const NETWORK_ID = self::PIG;
	public $width = 1.5;
	public $height = 1.0;

	private $saddled = false;

	public function initEntity() : void {
		$this->setMaxHealth(10);
		parent::initEntity();

		if((bool)$this->namedtag->getByte("Saddle", 0)) {
			$this->setSaddled(true);
		}
	}

	/**
	 * @param int $tickDiff
	 *
	 * @return bool
	 */
	public function entityBaseTick(int $tickDiff = 1) : bool {
		return parent::entityBaseTick($tickDiff);
		// TODO: follow carrots within 8 blocks
	}

	/**
	 * @return Item[]
	 */
	public function getDrops() : array {
		$drops = parent::getDrops();
		if(!$this->isBaby()) {
			if($this->isOnFire()) {
				$drops[] = ItemFactory::get(Item::COOKED_PORKCHOP, 0, mt_rand(1, 3));
			}else{
				$drops[] = ItemFactory::get(Item::PORKCHOP, 0, mt_rand(1, 3));
			}
			if(!empty($this->getArmorInventory()->getContents())) {
				$drops = array_merge($drops, $this->getArmorInventory()->getContents());
			}
		}
		return $drops;
	}

	public function getXpDropAmount() : int {
		$exp = parent::getXpDropAmount();
		if(!$this->isBaby()) {
			$exp += mt_rand(1, 3);
			return $exp;
		}
		return $exp;
	}

	/**
	 * @return string
	 */
	public function getName() : string {
		return "Pig";
	}

	/**
	 * @param Entity $entity
	 */
	public function onCollideWithEntity(Entity $entity) : void {
		// TODO: Implement onCollideWithEntity() method.
	}

	public function onPlayerLook(Player $player) : void {
		$hand = $player->getInventory()->getItemInHand();
		if(!$this->isBaby() and $hand->getId() instanceof Saddle) {
			$this->getDataPropertyManager()->setString(Entity::DATA_INTERACTIVE_TAG, "Saddle");
		}
	}

	public function onPlayerInteract(Player $player) : void {
		// TODO: Implement onPlayerInteract() method.
	}

	/**
	 * @param bool $saddled
	 *
	 * @return self
	 */
	public function setSaddled(bool $saddled) : self {
		$this->saddled = $saddled;
		$this->namedtag->setByte("Saddle", (int)$saddled);
		$this->setGenericFlag(self::DATA_FLAG_SADDLED, $saddled);
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isSaddled() : bool {
		return $this->saddled;
	}
}