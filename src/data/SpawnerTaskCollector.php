<?php

declare(strict_types=1);

namespace jasonwynn10\VanillaEntityAI\data;

use jasonwynn10\VanillaEntityAI\task\SpawnerTask;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\Position;
use pocketmine\world\World;

final class SpawnerTaskCollector{
	use SingletonTrait;

	/** @var array<string, SpawnerTask> $activeSpawners */
	private array $activeSpawners = [];

	public function add(Position $pos, SpawnerTask $task) : void{
		$this->activeSpawners[$pos->getWorld()->getFolderName() . ":" . World::blockHash($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ())] = $task;
	}

	public function remove(Position $pos) : void{
		$this->activeSpawners[$pos->getWorld()->getFolderName() . ":" . World::blockHash($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ())]?->getHandler()?->cancel();
		unset($this->activeSpawners[$pos->getWorld()->getFolderName() . ":" . World::blockHash($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ())]);
	}

	public function getAt(Position $pos) : ?SpawnerTask{
		return $this->activeSpawners[$pos->getWorld()->getFolderName() . ":" . World::blockHash($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ())];
	}
}
