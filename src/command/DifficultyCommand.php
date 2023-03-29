<?php

declare(strict_types=1);

namespace jasonwynn10\VanillaEntityAI\command;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\BaseCommand;
use jasonwynn10\VanillaEntityAI\command\argument\DifficultyArgument;
use jasonwynn10\VanillaEntityAI\entity\Hostile;
use jasonwynn10\VanillaEntityAI\lang\CustomKnownTranslationFactory;
use jasonwynn10\VanillaEntityAI\Main;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\lang\KnownTranslationFactory;
use pocketmine\permission\DefaultPermissionNames;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\World;

final class DifficultyCommand extends BaseCommand{

	public function __construct(Main $plugin){
		parent::__construct(
			$plugin,
			"difficulty",
			CustomKnownTranslationFactory::difficulty_command_description()
		);
	}

	protected function prepare() : void{
		$this->registerArgument(0, new DifficultyArgument("difficulty", false));
		$this->registerArgument(0, new IntegerArgument("difficulty", false));

		$this->setPermission('VanillaEntityAI.command.difficulty;' . DefaultPermissionNames::COMMAND_DIFFICULTY);
	}

	/**
	 * @phpstan-param array{
	 * 	difficulty: int
	 * } $args
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
		$difficulty = $args["difficulty"];
		if(Server::getInstance()->isHardcore()) {
			$difficulty = World::DIFFICULTY_HARD;
		}
		if($difficulty === -1)
			throw new InvalidCommandSyntaxException();

		if($sender instanceof Player) {
			$sender->getWorld()->setDifficulty($difficulty);
			if($sender->getWorld()->getDifficulty() === World::DIFFICULTY_PEACEFUL) { // TODO: check if world is enabled for VanillaEntityAI
				foreach($sender->getWorld()->getEntities() as $entity) {
					if($entity instanceof Hostile) {
						$entity->flagForDespawn();
					}
				}
			}
		}else{
			Server::getInstance()->getConfigGroup()->setConfigInt("difficulty", $difficulty);
			foreach(Server::getInstance()->getWorldManager()->getWorlds() as $world) {
				$world->setDifficulty($difficulty);
				if($world->getDifficulty() === World::DIFFICULTY_PEACEFUL) { // TODO: check if world is enabled for VanillaEntityAI
					foreach($world->getEntities() as $entity) {
						if($entity instanceof Hostile) {
							$entity->flagForDespawn();
						}
					}
				}
			}
		}
		self::broadcastCommandMessage($sender, KnownTranslationFactory::commands_difficulty_success((string) $difficulty));
	}
}
