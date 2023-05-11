<?php
declare(strict_types=1);

namespace jasonw4331\VanillaEntityAI\util;

use pocketmine\block\Block;
use pocketmine\entity\Entity;

final class SpawnVerifier{

	/**
	 * @phpstan-param class-string<Entity> $entityClass
	 */
	public static function canSpawn(string $entityClass, Block $floorBlock) : bool{
		if(!$floorBlock->getPosition()->isValid()) {
			throw new \InvalidArgumentException("Invalid position assigned to block");
		}

		return false; // TODO: Implement canSpawn() method.
	}

}