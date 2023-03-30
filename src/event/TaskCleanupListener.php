<?php
declare(strict_types=1);

namespace jasonwynn10\VanillaEntityAI\event;

use jasonwynn10\VanillaEntityAI\data\NaturalSpawnTaskCollector;
use jasonwynn10\VanillaEntityAI\data\SpawnerTaskCollector;
use jasonwynn10\VanillaEntityAI\Main;
use pocketmine\block\MonsterSpawner;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\world\WorldUnloadEvent;

final class TaskCleanupListener implements Listener{

	public function __construct(private Main $plugin){
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	/**
	 * @priority MONITOR
	 */
	public function onBlockBreak(BlockBreakEvent $event) : void{
		$block = $event->getBlock();

		if($block instanceof MonsterSpawner) {
			SpawnerTaskCollector::getInstance()->remove($block->getPosition()); // cancel task and remove reference
		}
	}

	/**
	 * @priority MONITOR
	 */
	public function onWorldClose(WorldUnloadEvent $event) : void{
		$world = $event->getWorld();
		NaturalSpawnTaskCollector::getInstance()->unloadWorld($world);
	}

}