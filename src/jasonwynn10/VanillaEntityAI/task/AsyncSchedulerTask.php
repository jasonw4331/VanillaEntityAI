<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\task;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class AsyncSchedulerTask extends Task {
	/**
	 * @param int $currentTick
	 */
	public function onRun(int $currentTick) {
		if(Server::getInstance()->getConfigBool("spawn-mobs", true))
			Server::getInstance()->getAsyncPool()->submitTask(new HostileSpawnTask());
		if(Server::getInstance()->getConfigBool("spawn-animals", true))
			Server::getInstance()->getAsyncPool()->submitTask(new PassiveSpawnTask());
		Server::getInstance()->getAsyncPool()->submitTask(new DespawnTask());
	}
}