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

abstract class CreatureBase extends Creature implements Linkable, Collidable {
	use SpawnableTrait, CollisionCheckingTrait;
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
	/** @var int $moveTime */
	protected $moveTime = 0;

	public function initEntity() : void {
		parent::initEntity();
	}

	/**
	 * @param float $dx
	 * @param float $dy
	 * @param float $dz
	 */
	public function move(float $dx, float $dy, float $dz) : void{
		$this->blocksAround = null;

		Timings::$entityMoveTimer->startTiming();

		$movX = $dx;
		$movY = $dy;
		$movZ = $dz;

		if($this->keepMovement) {
			$this->boundingBox->offset($dx, $dy, $dz);
		}else{
			$this->ySize *= 0.4;

			/*
			if($this->isColliding) { //With cobweb?
				$this->isColliding = false;
				$dx *= 0.25;
				$dy *= 0.05;
				$dz *= 0.25;
				$this->motionX = 0;
				$this->motionY = 0;
				$this->motionZ = 0;
			}
			*/

			$axisalignedbb = clone $this->boundingBox;

			/*$sneakFlag = $this->onGround and $this instanceof Player;

			if($sneakFlag) {
				for($mov = 0.05; $dx != 0.0 and count($this->level->getCollisionCubes($this, $this->boundingBox->getOffsetBoundingBox($dx, -1, 0))) === 0; $movX = $dx) {
					if($dx < $mov and $dx >= -$mov) {
						$dx = 0;
					}elseif($dx > 0) {
						$dx -= $mov;
					}else{
						$dx += $mov;
					}
				}

				for(; $dz != 0.0 and count($this->level->getCollisionCubes($this, $this->boundingBox->getOffsetBoundingBox(0, -1, $dz))) === 0; $movZ = $dz) {
					if($dz < $mov and $dz >= -$mov) {
						$dz = 0;
					}elseif($dz > 0) {
						$dz -= $mov;
					}else{
						$dz += $mov;
					}
				}

				//TODO: big messy loop
			}*/

			assert(abs($dx) <= 20 and abs($dy) <= 20 and abs($dz) <= 20, "Movement distance is excessive: dx=$dx, dy=$dy, dz=$dz");

			$list = $this->level->getCollisionCubes($this, $this->level->getTickRate() > 1 ? $this->boundingBox->offsetCopy($dx, $dy, $dz) : $this->boundingBox->addCoord($dx, $dy, $dz), false);

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
				}else{
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

	/**
	 * @param Player $player
	 */
	public function onPlayerLook(Player $player) : void {
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
		if($f <= 0){
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