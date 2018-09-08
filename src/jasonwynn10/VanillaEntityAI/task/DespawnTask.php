<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\task;

use pocketmine\entity\Creature;
use pocketmine\entity\Human;
use pocketmine\entity\Monster;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class DespawnTask extends Task {
	public function onRun(int $currentTick) {
		foreach(Server::getInstance()->getOnlinePlayers() as $player) {
			$centerX = $player->z >> 4;
			$centerZ = $player->z >> 4;
			for($X = $centerX - 8; $X < $centerX + 8; $X++) {
				for($Z = $centerZ - 8; $Z < $centerZ + 8; $Z++) {
					$chunk = $player->getLevel()->getChunk($X, $Z, true);
					foreach($chunk->getEntities() as $entity) {
						if($entity instanceof Monster and $entity->distance($player) > 128 and $entity->getLevel()->getFullLight($entity->floor()) > 8) {
							$entity->flagForDespawn();
						}elseif($entity instanceof Creature and $entity->distance($player) > 128 and $entity->getLevel()->getFullLight($entity->floor()) < 7 and !$entity instanceof Human) {
							$entity->flagForDespawn();
						}elseif($entity instanceof Monster and $entity->distance($player) > 32 and $entity->getLevel()->getFullLight($entity->floor()) > 8 and mt_rand(1, 50) === 1) {
							$entity->flagForDespawn();
						}elseif($entity instanceof Creature and $entity->distance($player) > 32 and $entity->getLevel()->getFullLight($entity->floor()) < 7 and !$entity instanceof Human and mt_rand(1, 50) === 1) {
							$entity->flagForDespawn();
						}
					}
				}
			}
		}
	}
}