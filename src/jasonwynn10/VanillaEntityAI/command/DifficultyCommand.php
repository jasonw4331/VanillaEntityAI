<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\command;

use jasonwynn10\VanillaEntityAI\EntityAI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\lang\TranslationContainer;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class DifficultyCommand extends \pocketmine\command\defaults\DifficultyCommand implements PluginIdentifiableCommand {
	public function execute(CommandSender $sender, string $commandLabel, array $args) {
		if(!$this->testPermission($sender)) {
			return true;
		}
		if(count($args) !== 1) {
			throw new InvalidCommandSyntaxException();
		}
		$difficulty = Level::getDifficultyFromString($args[0]);
		if($sender->getServer()->isHardcore()) {
			$difficulty = Level::DIFFICULTY_HARD;
		}
		if($difficulty !== -1) {
			if($sender instanceof Player) {
				$sender->getLevel()->setDifficulty($difficulty);
				foreach($sender->getLevel()->getEntities() as $entity) {
					$entity->flagForDespawn();
				}
			}else {
				$sender->getServer()->setConfigInt("difficulty", $difficulty);
				foreach($sender->getServer()->getLevels() as $level) {
					$level->setDifficulty($difficulty);
					foreach($level->getEntities() as $entity) {
						$entity->flagForDespawn();
					}
				}
			}
			Command::broadcastCommandMessage($sender, new TranslationContainer("commands.difficulty.success", [$difficulty]));
		}else {
			throw new InvalidCommandSyntaxException();
		}
		return true;
	}

	public function getPlugin() : Plugin {
		return EntityAI::getInstance();
	}
}