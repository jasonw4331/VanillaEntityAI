<?php
declare(strict_types=1);

namespace jasonw4331\VanillaEntityAI\command\argument;

use CortexPE\Commando\args\StringEnumArgument;
use pocketmine\command\CommandSender;
use pocketmine\world\World;

final class DifficultyArgument extends StringEnumArgument{
	protected const VALUES = [
		"p" => World::DIFFICULTY_PEACEFUL,
		"peaceful" => World::DIFFICULTY_PEACEFUL,
		"e" => World::DIFFICULTY_EASY,
		"easy" => World::DIFFICULTY_EASY,
		"n" => World::DIFFICULTY_NORMAL,
		"normal" => World::DIFFICULTY_NORMAL,
		"h" => World::DIFFICULTY_HARD,
		"hard" => World::DIFFICULTY_HARD
	];

	public function parse(string $argument, CommandSender $sender) : mixed{
		return $this->getValue($argument);
	}

	public function getTypeName() : string{
		return "Difficulty";
	}
}