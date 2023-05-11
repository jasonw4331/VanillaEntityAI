<?php
declare(strict_types=1);

namespace jasonw4331\VanillaEntityAI\task;

use jasonw4331\VanillaEntityAI\Main;
use pocketmine\scheduler\Task;
use pocketmine\world\World;

final class NaturalAnimalSpawnTask extends Task{

	public function __construct(private Main $plugin, private World $world){
	}

	public function onRun() : void{
		// TODO: Implement onRun() method.
	}

	public function getWorld() : World{
		return $this->world;
	}
}