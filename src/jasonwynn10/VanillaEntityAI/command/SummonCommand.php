<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\command;

use jasonwynn10\VanillaEntityAI\EntityAI;
use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class SummonCommand extends VanillaCommand implements PluginIdentifiableCommand {
	public function __construct(string $name) {
		parent::__construct($name, "Summons an entity.", "/summon <entityType: EntityType> [spawnPos: x y z]");
		$this->setPermission("pocketmine.command.summon");
	}

	/**
	 * @param CommandSender $sender
	 * @param string $label
	 * @param array $args
	 *
	 * @return bool|mixed
	 * @throws \ReflectionException
	 */
	public function execute(CommandSender $sender, string $label, array $args) {
		if(!$this->testPermission($sender) or !$sender instanceof Player) {
			return true;
		}
		$args = array_values(array_filter($args, function($arg) {
			return $arg !== "";
		}));
		if(count($args) < 1 or (count($args) > 1 and count($args) < 4) or count($args) > 4) {
			throw new InvalidCommandSyntaxException();
		}
		$entityId = 0;
		foreach(array_keys(EntityAI::getEntities()) as $class) {
			/** @noinspection PhpUnhandledExceptionInspection */
			$reflectionClass = new \ReflectionClass($class);
			if(is_numeric($args[0]) and $reflectionClass->getConstant("NETWORK_ID") === (int)$args[0]) {
				$entityId = $reflectionClass->getConstant("NETWORK_ID");
				break;
			}elseif(strtolower($args[0]) === strtolower($reflectionClass->getShortName())) {
				$entityId = $reflectionClass->getConstant("NETWORK_ID");
				break;
			}
		}
		if($entityId <= 0) {
			$sender->sendMessage(TextFormat::RED . "Syntax error: Unexpected \"$args[0]\": at \"/summon >>$args[0]<< $args[1] $args[2] $args[3]\"");
			return true;
		}
		if(count($args) > 1 and count($args) < 4) {
			$x = $this->getRelativeDouble($sender->x, $sender, $args[$pos = 2]);
			$y = $this->getRelativeDouble($sender->y, $sender, $args[++$pos], 0, $sender->getLevel()->getWorldHeight());
			$z = $this->getRelativeDouble($sender->z, $sender, $args[++$pos]);
		}else {
			$x = $sender->x;
			$y = $sender->y;
			$z = $sender->z;
		}
		$entity = Entity::createEntity($entityId, $sender->getLevel(), Entity::createBaseNBT(new Vector3($x, $y, $z)));
		$entity->spawnToAll();
		$sender->sendMessage("Object successfully spawned");
		return true;
	}

	public function getPlugin() : Plugin {
		return EntityAI::getInstance();
	}
}