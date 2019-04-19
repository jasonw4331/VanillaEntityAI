<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity;

use jasonwynn10\VanillaEntityAI\entity\passiveaggressive\Player;
use pocketmine\block\Block;
use pocketmine\entity\Creature;
use pocketmine\entity\Entity;
use pocketmine\level\Position;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\timings\Timings;

abstract class CreatureBase extends Creature implements Linkable, Collidable, Lookable {
	use SpawnableTrait, CollisionCheckingTrait, LinkableTrait;
	/** @var float $speed */
	protected $speed = 1.0;
	/** @var float $stepHeight */
	protected $stepHeight = 1.0;
	/** @var Position|null $target */
	protected $target = null;
	/** @var bool $persistent */
	protected $persistent = false;
	/** @var int $moveTime */
	protected $moveTime = 0;

	/**
	 * Returns the Vector3 side number right of the specified one
	 *
	 * @param int $side 0-5 one of the Vector3::SIDE_* constants
	 *
	 * @return int
	 *
	 * @throws \InvalidArgumentException if an invalid side is supplied
	 */
	public static function getRightSide(int $side) : int {
		if($side >= 0 and $side <= 5) {
			return $side ^ 0x01; // TODO: right now it gives the opposite side...
		}
		throw new \InvalidArgumentException("Invalid side $side given to getRightSide");
	}

	/**
	 * @param Position $spawnPos
	 * @param CompoundTag|null $spawnData
	 *
	 * @return null|CreatureBase
	 */
	public static function spawnMob(Position $spawnPos, ?CompoundTag $spawnData = null) : ?CreatureBase {
		return null;
	}

	public function initEntity() : void {
		parent::initEntity();
	}

	/**
	 * @param float $dx
	 * @param float $dy
	 * @param float $dz
	 */
	public function move(float $dx, float $dy, float $dz) : void {
		$this->blocksAround = null;
		Timings::$entityMoveTimer->startTiming();
		$movX = $dx;
		$movY = $dy;
		$movZ = $dz;
		if($this->keepMovement) {
			$this->boundingBox->offset($dx, $dy, $dz);
		}else {
			$this->ySize *= 0.4;
			$axisalignedbb = clone $this->boundingBox;
			$list = $this->level->getCollisionCubes($this, $this->boundingBox->addCoord($dx, $dy, $dz), false);
			foreach($list as $bb) {
				$dy = $bb->calculateYOffset($this->boundingBox, $dy);
			}
			$this->boundingBox->offset(0, $dy, 0);
			$fallingFlag = ($this->onGround or ($dy != $movY and $movY < 0));
			foreach($list as $bb) {
				$dx = $bb->calculateXOffset($this->boundingBox, $dx);
			}
			$this->boundingBox->offset($dx, 0, 0);
			foreach($list as $bb) {
				$dz = $bb->calculateZOffset($this->boundingBox, $dz);
			}
			$this->boundingBox->offset(0, 0, $dz);
			if($this->stepHeight > 0 and $fallingFlag and $this->ySize < 0.05 and ($movX != $dx or $movZ != $dz)) {
				$cx = $dx;
				$cy = $dy;
				$cz = $dz;
				$dx = $movX;
				$dy = $this->stepHeight;
				$dz = $movZ;
				$axisalignedbb1 = clone $this->boundingBox;
				$this->boundingBox->setBB($axisalignedbb);
				$list = $this->level->getCollisionCubes($this, $this->boundingBox->addCoord($dx, $dy, $dz), false);
				foreach($list as $bb) {
					$dy = $bb->calculateYOffset($this->boundingBox, $dy);
				}
				$this->boundingBox->offset(0, $dy, 0);
				foreach($list as $bb) {
					$dx = $bb->calculateXOffset($this->boundingBox, $dx);
				}
				$this->boundingBox->offset($dx, 0, 0);
				foreach($list as $bb) {
					$dz = $bb->calculateZOffset($this->boundingBox, $dz);
				}
				$this->boundingBox->offset(0, 0, $dz);
				if(($cx ** 2 + $cz ** 2) >= ($dx ** 2 + $dz ** 2)) {
					$dx = $cx;
					$dy = $cy;
					$dz = $cz;
					$this->boundingBox->setBB($axisalignedbb1);
				}else {
					$block = $this->level->getBlock($this->getSide(Vector3::SIDE_DOWN));
					$blockBB = $block->getBoundingBox() ?? new AxisAlignedBB($block->x, $block->y, $block->z, $block->x + 1, $block->y + 1, $block->z + 1);
					$this->ySize += $blockBB->maxY - $blockBB->minY;
				}
			}
		}
		$this->x = ($this->boundingBox->minX + $this->boundingBox->maxX) / 2;
		$this->y = $this->boundingBox->minY - $this->ySize;
		$this->z = ($this->boundingBox->minZ + $this->boundingBox->maxZ) / 2;
		$this->checkChunks();
		$this->checkBlockCollision();
		$this->checkGroundState($movX, $movY, $movZ, $dx, $dy, $dz);
		$this->updateFallState($dy, $this->onGround);
		if($movX != $dx) {
			$this->motion->x = 0;
		}
		if($movY != $dy) {
			$this->motion->y = 0;
		}
		if($movZ != $dz) {
			$this->motion->z = 0;
		}
		//TODO: vehicle collision events (first we need to spawn them!)
		Timings::$entityMoveTimer->stopTiming();
	}

	/**
	 * @param Entity $entity
	 *
	 * @return bool
	 */
	public function hasLineOfSight(Entity $entity) : bool {
		$distance = (int) $this->add(0, $this->eyeHeight)->distance($entity);
		if($distance > 1) {
			return $this->distance($entity) < 1 or empty($this->getLineOfSight($distance));
		}
		return true;
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
	 * @param Player $player
	 */
	public function onPlayerLook(Player $player) : void {
		// TODO: Implement onPlayerLook() method.
	}

	/**
	 * @param Entity $entity
	 */
	public function onCollideWithEntity(Entity $entity) : void {
	}

	/**
	 * @param Block $block
	 */
	public function onCollideWithBlock(Block $block) : void {
	}

	public function push(AxisAlignedBB $source) : void {
		$base = 0.15;
		$x = ($source->minX + $source->maxX) / 2;
		$z = ($source->minZ + $source->maxZ) / 2;
		$f = sqrt($x * $x + $z * $z);
		if($f <= 0) {
			return;
		}
		$f = 1 / $f;
		$motion = clone $this->motion;
		$motion->x /= 2;
		$motion->z /= 2;
		$motion->x += $x * $f * $base;
		$motion->z += $z * $f * $base;
		$this->setMotion($motion);
	}
}