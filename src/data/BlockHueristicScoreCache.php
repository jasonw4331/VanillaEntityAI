<?php
declare(strict_types=1);

namespace jasonw4331\VanillaEntityAI\data;

use pocketmine\math\Vector3;
use pocketmine\world\World;

final class BlockHeuristicScoreCache{
	/** @var float[] $cache */
	private static array $cache = [];

	public static function setBlockScore(Vector3 $pos, float $score) : void{
		self::setBlockScoreAt($pos->x, $pos->y, $pos->z, $score);
	}

	public static function setBlockScoreAt(int|float $x, int|float $y, int|float $z, float $score) : void{
		self::$cache[World::blockHash($x, $y, $z)] = $score;
	}

	public static function getBlockScore(Vector3 $pos) : ?float{
		return self::getBlockScoreAt($pos->x, $pos->y, $pos->z);
	}

	public static function getBlockScoreAt(int|float $x, int|float $y, int|float $z) : ?float{
		return self::$cache[World::blockHash($x, $y, $z)] ?? null;
	}

}