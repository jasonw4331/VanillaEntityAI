<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\hostile;

use jasonwynn10\VanillaEntityAI\entity\Linkable;
use pocketmine\entity\Living;
use pocketmine\level\Position;
use pocketmine\nbt\tag\CompoundTag;

class MagmaCube extends Slime implements CustomMonster {
	public const NETWORK_ID = self::MAGMA_CUBE;
	public $width = 1.2;
	public $height = 1.2;

	/**
	 * @return string
	 */
	public function getName(): string {
		return "Magma Cube";
	}

	/**
	 * @param Position $spawnPos
	 * @param CompoundTag|null $spawnData
	 *
	 * @return null|Living
	 */
	public static function spawnMob(Position $spawnPos, ?CompoundTag $spawnData = null) : ?Living {
		// TODO: Implement spawnMob() method.
	}

	/**
	 * @return Linkable|null
	 */
	public function getLink() : ?Linkable {
		// TODO: Implement getLink() method.
	}

	/**
	 * @param Linkable $entity
	 */
	public function setLink(Linkable $entity) {
		// TODO: Implement setLink() method.
	}
}