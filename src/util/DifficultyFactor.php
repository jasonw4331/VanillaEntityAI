<?php

declare(strict_types=1);

namespace jasonw4331\VanillaEntityAI\util;

use jasonw4331\VanillaEntityAI\data\InhabitedChunkTimeTracker;
use pocketmine\world\World;
use function array_reduce;
use function floor;
use function max;
use function microtime;

final class DifficultyFactor{

	public static function getClumpedRegionalDifficulty(World $world, int $x, int $z) : float {
		$regionalDifficulty = self::getRegionalDifficulty($world, $x, $z);
		return ($regionalDifficulty < 2.0) ? 0.0 : (($regionalDifficulty > 4.0) ? 1.0 : ($regionalDifficulty - 2.0) / 2.0);
	}

	public static function getRegionalDifficulty(World $world, int $x, int $z) : float {
		$totalPlayTime = array_reduce($world->getPlayers(), static function($total, $player) {
			$time = microtime(true) - (($player->getLastPlayed() ?? 0) / 1000);
			$hours = $time >= 3600 ? floor(($time % (3600 * 24)) / 3600) : 0;
			return $total + $hours;
		}, 0);

		$totalTimeFactor = $totalPlayTime > 21 ? 0.25 : (($totalPlayTime < 20 ? 0 : (($totalPlayTime * 20 * 60 * 60) - 72000) / 5760000));

		$inhabitedTime = InhabitedChunkTimeTracker::getTimeAt($world, $x, $z);
		$chunkFactor = $inhabitedTime > 50 ? 1 : ($inhabitedTime * 20 * 60 * 60) / 3600000;
		$chunkFactor *= $world->getDifficulty() !== World::DIFFICULTY_HARD ? 3 / 4 : 1;

		$phaseTime = $world->getTime() / World::TIME_FULL % 5;
		$moonPhases = [1.0, 0.75, 0.5, 0.25, 0.0];
		$moonPhase = $moonPhases[$phaseTime - 1] ?? 0;
		$chunkFactor += max($moonPhase / 4, $totalTimeFactor);
		$chunkFactor /= $world->getDifficulty() === World::DIFFICULTY_EASY ? 2 : 1;

		$regionalDifficulty = 0.75 + $totalTimeFactor + $chunkFactor;
		$regionalDifficulty *= $world->getDifficulty() === World::DIFFICULTY_NORMAL ? 2 : ($world->getDifficulty() === World::DIFFICULTY_HARD ? 3 : 1);
		return $regionalDifficulty;
	}
}
