<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\block;

use jasonwynn10\VanillaEntityAI\tile\MobSpawner;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;

class MonsterSpawner extends \pocketmine\block\MonsterSpawner {
	protected $entityId = -1;

	public function canBeActivated(): bool {
		return true;
	}

	public function onActivate(Item $item, Player $player = null): bool {
		if($player instanceof Player and $item->getId() === Item::SPAWN_EGG) {
			$t = $this->getLevel()->getTile($this);
			$spawner = null;
			if($t instanceof MobSpawner) {
				$spawner = $t;
			}else {
				/** @var MobSpawner $spawner */
				$spawner = MobSpawner::createTile(MobSpawner::MOB_SPAWNER, $this->getLevel(), MobSpawner::createNBT($this));
			}
			$spawner->setEntityId($item->getDamage());
		}
		return true;
	}

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null): bool {
		if($item->getDamage() > 9) {
			$this->meta = 0;
			$this->entityId = $item->getDamage();
			$return = parent::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player);
			MobSpawner::createTile(MobSpawner::MOB_SPAWNER, $this->getLevel(), MobSpawner::createNBT($this));
		}else {
			$return = parent::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player);
		}
		return $return;
	}
}