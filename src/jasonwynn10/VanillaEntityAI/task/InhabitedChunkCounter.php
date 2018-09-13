<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\task;

use pocketmine\scheduler\Task;
use pocketmine\Server;

class InhabitedChunkCounter extends Task {
	public function onRun(int $currentTick) {
		foreach(Server::getInstance()->getLevels() as $level) {
			foreach($level->getPlayers() as $player) {
				$chunk = $level->getChunk($player->x >> 4, $player->z >> 4);
				if($chunk !== null) {
					$chunk->inhabitedTime += 1;
				}
			}
		}
	}
}