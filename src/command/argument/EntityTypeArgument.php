<?php
declare(strict_types=1);

namespace jasonw4331\VanillaEntityAI\command\argument;

use CortexPE\Commando\args\StringEnumArgument;
use pocketmine\command\CommandSender;

final class EntityTypeArgument extends StringEnumArgument{
	protected const VALUES = [
		// TODO: add all entity types
	];

	public function parse(string $argument, CommandSender $sender) : mixed{
		return $this->getValue($argument);
	}

	public function getTypeName() : string{
		return "EntityType";
	}
}