<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\block;

use jasonwynn10\VanillaEntityAI\entity\passiveaggressive\SnowGolem;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;

class Snow extends \pocketmine\block\Snow {
	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool{
		if(($block1 = $this->getSide(Vector3::SIDE_UP, 2)) instanceof Pumpkin and ($block2 = $this->getSide(Vector3::SIDE_UP, 1)) instanceof Snow) {
			$this->level->setBlock($this, Block::get(Block::AIR));
			$this->level->setBlock($block1, Block::get(Block::AIR));
			$this->level->setBlock($block2, Block::get(Block::AIR));
			$entity = Entity::createEntity(SnowGolem::NETWORK_ID, $this->level, SnowGolem::createBaseNBT($this));
			$entity->spawnToAll();
			return false;
		}elseif(($block1 = $this->getSide(Vector3::SIDE_UP, 1)) instanceof Pumpkin and ($block2 = $this->getSide(Vector3::SIDE_DOWN, 1)) instanceof Snow) {
			$this->level->setBlock($this, Block::get(Block::AIR));
			$this->level->setBlock($block1, Block::get(Block::AIR));
			$this->level->setBlock($block2, Block::get(Block::AIR));
			$entity = Entity::createEntity(SnowGolem::NETWORK_ID, $this->level, SnowGolem::createBaseNBT($block2));
			$entity->spawnToAll();
			return false;
		}

		return parent::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}
}