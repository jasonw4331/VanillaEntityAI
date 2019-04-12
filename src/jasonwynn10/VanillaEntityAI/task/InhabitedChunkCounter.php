<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\task;

use jasonwynn10\VanillaEntityAI\EntityAI;
use pocketmine\level\Level;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class InhabitedChunkCounter extends Task {
	public function onRun(int $currentTick) {
		foreach(Server::getInstance()->getOnlinePlayers() as $player) {
			$level = $player->level;
			$chunk = $player->chunk;
			if($level !== null and $chunk !== null) {
				$hash = Level::chunkHash($chunk->getX(), $chunk->getZ());
				if(!isset(EntityAI::$chunkCounter[$hash])) {
					EntityAI::$chunkCounter[$hash.":".$level->getFolderName()] = 0;
				}
				EntityAI::$chunkCounter[$hash.":".$level->getFolderName()] += 1;
			}
		}
	}
}