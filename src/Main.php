<?php
declare(strict_types=1);

namespace jasonwynn10\VanillaEntityAI;

use CortexPE\Commando\PacketHooker;
use jasonwynn10\VanillaEntityAI\command\DifficultyCommand;
use jasonwynn10\VanillaEntityAI\command\SummonCommand;
use jasonwynn10\VanillaEntityAI\event\EventListener;
use jasonwynn10\VanillaEntityAI\event\TaskCleanupListener;
use pocketmine\entity\EntityFactory;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

final class Main extends PluginBase{
	use SingletonTrait{
		reset as private;
		setInstance as private;
	}

	public function onLoad() : void{
		$this->setInstance($this);

		$this->registerEntities();
	}

	private function registerEntities() : void{
		$factory = EntityFactory::getInstance();
		// TODO: Register entities
	}

	public function onEnable() : void{
		PacketHooker::register($this);
		$this->getServer()->getCommandMap()->registerAll($this->getName(), [
			new DifficultyCommand($this),
			new SummonCommand($this),
		]);

		new EventListener($this);
		new TaskCleanupListener($this);
	}
}