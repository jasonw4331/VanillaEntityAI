<?php
declare(strict_types=1);

namespace jasonwynn10\VanillaEntityAI\data;

use jasonwynn10\VanillaEntityAI\task\NaturalAnimalSpawnTask;
use jasonwynn10\VanillaEntityAI\task\NaturalMonsterSpawnTask;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\World;

final class NaturalSpawnTaskCollector{
	use SingletonTrait;

	/** @var array<string, NaturalMonsterSpawnTask> $activeMonsterTasks */
	private array $activeMonsterTasks = [];

	/** @var array<string, NaturalAnimalSpawnTask> $activeAnimalTasks */
	private array $activeAnimalTasks = [];

	public function addMonster(NaturalMonsterSpawnTask $task) : void{
		assert(!isset($this->activeMonsterTasks[$task->getWorld()->getFolderName()]), "Tried to add a monster spawn task to a world which already has one");
		$this->activeMonsterTasks[$task->getWorld()->getFolderName()] = $task;
	}

	public function addAnimal(NaturalAnimalSpawnTask $task) : void{
		assert(!isset($this->activeAnimalTasks[$task->getWorld()->getFolderName()]), "Tried to add an animal spawn task to a world which already has one");
		$this->activeAnimalTasks[$task->getWorld()->getFolderName()] = $task;
	}

	public function updateWorldSettings(World $world) : void{
		if($world->getDifficulty() === World::DIFFICULTY_PEACEFUL) {
			$this->activeMonsterTasks[$world->getFolderName()]?->getHandler()?->cancel();
			unset($this->activeMonsterTasks[$world->getFolderName()]);
		}
	}

	public function unloadWorld(World $world) : void{
		$this->activeMonsterTasks[$world->getFolderName()]?->getHandler()?->cancel();
		unset($this->activeMonsterTasks[$world->getFolderName()]);
		$this->activeAnimalTasks[$world->getFolderName()]?->getHandler()?->cancel();
		unset($this->activeAnimalTasks[$world->getFolderName()]);
	}

}