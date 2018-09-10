<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\command;

use jasonwyunn10\VanillaEntityAI\EntityAI;

class SummonCommand extends Command {
	public function __construct(EntityAI $plugin) {
		parent::__construct(
			$name,
			"%pocketmine.command.summon.description",
			"%commands.summon.usage"
		);
		$this->setPermission("pocketmine.command.summon");
		$this->plugin = $plugin;
	}
	public function execute(CommandSender $sender, string $label, array $args) {
		if(!$this->testPermission($sender)) {
			return true;
		}

		$args = array_values(array_filter($args, function($arg) {
			return $arg !== "";
		}));

		if(count($args) < 1 or (count($args) > 1 and count($args) < 4) or count($args) > 4) {
			throw new InvalidCommandSyntaxException();
		}

		$entityId = 0; // TODO: find entity id by id or name

		if(isset($args[1]) and isset($args[2]) and isset($args[3])) {
			$pos = 1;
			$x = (float)$args[$pos++];
			$y = (float)$args[$pos++];
			$z = (float)$args[$pos++];
		}else{
			$x = $sender->x;
			$y = $sender->y;
			$z = $sender->z;
		}

		$nbt = Entity::createBaseNBT(new Vector3($x, $y, $z), null, $sender->getYaw(), $sender->getPitch());
		$entity = Entity::createEntity($entityId, $sender->getLevel(), $nbt);
		$entity->spawnToAll();
	}
}
