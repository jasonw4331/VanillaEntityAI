<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity;

use pocketmine\entity\Creature;
use pocketmine\entity\Entity;
use pocketmine\level\Position;
use pocketmine\nbt\tag\CompoundTag;

abstract class CreatureBase extends Creature implements Linkable {
	use SpawnableTrait;
	/** @var float $speed */
	protected $speed = 1.0;
	/** @var float $stepHeight */
	protected $stepHeight = 1.0;
	/** @var Position|null $target */
	protected $target = null;
	/** @var bool $persistent */
	protected $persistent = false;
	/** @var Linkable|null $linkedEntity */
	protected $linkedEntity;

	/**
	 * @param Position $spawnPos
	 * @param CompoundTag|null $spawnData
	 *
	 * @return null|CreatureBase
	 */
	public static function spawnMob(Position $spawnPos, ?CompoundTag $spawnData = null) : ?CreatureBase {
		return null;
	}

	/**
	 * @return Position|null
	 */
	public function getTarget() : ?Position {
		return $this->target;
	}

	/**
	 * @param Position|null $target
	 *
	 * @return CreatureBase
	 */
	public function setTarget(?Position $target) : self {
		$this->target = $target;
		if($target instanceof Entity or is_null($target)) {
			$this->setTargetEntity($target);
		}
		return $this;
	}

	/**
	 * @return float
	 */
	public function getSpeed() : float {
		return $this->speed;
	}

	/**
	 * @param float $speed
	 *
	 * @return CreatureBase
	 */
	public function setSpeed(float $speed) : self {
		$this->speed = $speed;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isPersistent() : bool {
		return $this->persistent;
	}

	/**
	 * @param bool $persistent
	 *
	 * @return CreatureBase
	 */
	public function setPersistence(bool $persistent) : self {
		$this->persistent = $persistent;
		return $this;
	}

	/**
	 * @return Linkable|null
	 */
	public function getLink() : ?Linkable {
		return $this->linkedEntity;
	}

	/**
	 * @param Linkable|null $entity
	 *
	 * @return CreatureBase
	 */
	public function setLink(?Linkable $entity) : self {
		$this->linkedEntity = $entity;
		return $this;
	}
}