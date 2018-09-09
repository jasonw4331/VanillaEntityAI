<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\task;

use jasonwynn10\VanillaEntityAI\entity\hostile\CustomMonster;
use pocketmine\entity\Creature;
use pocketmine\entity\Human;
use pocketmine\level\format\Chunk;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class DespawnTask extends Task {
	public function onRun(int $currentTick) {
		foreach(Server::getInstance()->getLevels() as $level) {
			/** @var Chunk[] $chunks */
			$chunks = [];
			foreach($level->getPlayers() as $player) {
				$centerX = $player->z >> 4;
				$centerZ = $player->z >> 4;
				for($X = $centerX - 8; $X < $centerX + 8; $X++) {
					for($Z = $centerZ - 8; $Z < $centerZ + 8; $Z++) {
						if(!isset($chunks[$X . ":" . $Z])) {
							$chunks[$X . ":" . $Z] = $level->getChunk($X, $Z, true);
						}
					}
				}
			}
			foreach($chunks as $chunk) {
				foreach($chunk->getEntities() as $entity) {
					$closeA = false;
					$closeB = false;
					foreach($level->getPlayers() as $player) {
						if($entity->distance($player) < 128) {
							$closeA = true;
							break;
						}
						if($entity->distance($player) < 32) {
							$closeB = true;
							break;
						}
					}
					if($entity instanceof CustomMonster and !$closeA and $entity->getLevel()->getFullLight($entity->floor()) > 8) {
						$entity->flagForDespawn();
					}elseif($entity instanceof Creature and !$closeA and $entity->getLevel()->getFullLight($entity->floor()) < 7 and !$entity instanceof Human) {
						$entity->flagForDespawn();
					}elseif($entity instanceof CustomMonster and !$closeB and !$entity->getTarget() instanceof Player and $entity->getLevel()->getFullLight($entity->floor()) > 8 and mt_rand(1, 50) === 1) {
						$entity->flagForDespawn();
					}elseif($entity instanceof Creature and !$closeB and !$entity->getTarget() instanceof Player and $entity->getLevel()->getFullLight($entity->floor()) < 7 and !$entity instanceof Human and mt_rand(1, 50) === 1) {
						$entity->flagForDespawn();
					}
				}
			}
		}
	}
}