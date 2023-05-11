<?php

declare(strict_types=1);

namespace jasonw4331\VanillaEntityAI\entity\trait;

use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;

trait VanillaMobTrait{
	use VanillaEntityTrait;
	private int $leashHolderId = -1;
	private bool $canPickupItems = true;
	private bool $hasSetCanPickupItems = false;
	private int $limitedLife = 0;
	private int $hurtTime = 0;
	private int $deathTime = 0;
	private bool $isSurfaceMob = false;
	private bool $naturalSpawn = false;
	private bool $hasBoundOrigin = false;
	private int $boundX = 0;
	private int $boundY = 0;
	private int $boundZ = 0;
	private bool $isInRaid = false;
	private bool $reactToBell = false;
	private bool $wantsToBeJockey = false;

	public function saveMobNBT(CompoundTag $nbt) : CompoundTag{
		$nbt = $this->saveEntityNBT($nbt);

		$nbt->setLong('limitedLife', $this->limitedLife); // only set if Evoker Fangs

		$nbt->setLong('LeasherID', $this->leashHolderId); // -1 if not leashed

		$nbt->setInt('TradeTier', $this->tradeTier); // TODO: move to trader trait
		$nbt->setInt('TradeExperience', $this->tradeExperience); // TODO: move to trader trait
		$nbt->setTag('persistingOffers', CompoundTag::create()); // TODO: move to trader trait
		$nbt->setInt('persistingRiches', $this->persistingRiches); // TODO: move to trader trait

		$nbt->setShort('HurtTime', $this->hurtTime); // ticks to be red after attack
		$nbt->setShort('DeathTime', $this->deathTime); // ticks since death
		$nbt->setByte('Dead', !$this->isAlive() ? 1 : 0);
		$nbt->setShort('AttackTime', $this->attackTime); // ticks since was last attacked

		$nbt->setByte('Surface', $this->isSurfaceMob ? 1 : 0);
		$nbt->setByte('NaturalSpawn', $this->naturalSpawn ? 1 : 0);

		$nbt->setLong('TargetID', $this->targetId ?? -1); // -1 if no target // set if mob angry

		$nbt->setByte('hasBoundOrigin', $this->hasBoundOrigin ? 1 : 0); // only 1 if Vex spawned by evoker
		$nbt->setInt('boundX', $this->boundX); // only set if hasBoundOrigin
		$nbt->setInt('boundY', $this->boundY); // only set if hasBoundOrigin
		$nbt->setInt('boundZ', $this->boundZ); // only set if hasBoundOrigin

		$attributes = [];
		foreach($this->getAttributeMap()->getAll() as $attribute){
			$attributes[] = CompoundTag::create()
				->setString('Name', $attribute->getId())
				->setFloat('Base', $attribute->getDefaultValue())
				->setFloat('DefaultValue', $attribute->getDefaultValue())
				->setFloat('DefaultMin', $attribute->getMinValue())
				->setFloat('DefaultMax', $attribute->getMaxValue())
				->setFloat('Min', $attribute->getMinValue())
				->setFloat('Max', $attribute->getMaxValue())
				->setFloat('Current', $attribute->getValue());
		}
		$nbt->setTag('Attributes', new ListTag($attributes));

		//$nbt->setTag('ActiveEffects', new ListTag([])); // already handled by Living
		//$nbt->setFloat('BodyRot', $this->bodyRotation); // may not exist // unknown
		$nbt->setByte('WantsToBeJockey', $this->wantsToBeJockey ? 1 : 0); // not always set // unknown
		$nbt->setByte('IsInRaid', $this->isInRaid ? 1 : 0);
		$nbt->setByte('ReactToBell', $this->reactToBell ? 1 : 0);
		//$nbt->setLong('TargetCaptainID', $this->targetCaptainId); // used by Pillager and Vindicator

		return $nbt;
	}

	public function getLeashHolderId() : int{
		return $this->leashHolderId;
	}

	public function setLeashHolderId(int $leashHolderId) : self{
		$this->leashHolderId = $leashHolderId;
		return $this;
	}

	public function getHurtTime() : int{
		return $this->hurtTime;
	}

	public function setHurtTime(int $hurtTime) : self{
		$this->hurtTime = $hurtTime;
		return $this;
	}

	public function getDeathTime() : int{
		return $this->deathTime;
	}

	public function setDeathTime(int $deathTime) : self{
		$this->deathTime = $deathTime;
		return $this;
	}

	public function isSurfaceMob() : bool{
		return $this->isSurfaceMob;
	}

	public function setIsSurfaceMob(bool $isSurfaceMob) : self{
		$this->isSurfaceMob = $isSurfaceMob;
		return $this;
	}

	public function isNaturalSpawn() : bool{
		return $this->naturalSpawn;
	}

	public function setNaturalSpawn(bool $naturalSpawn) : self{
		$this->naturalSpawn = $naturalSpawn;
		return $this;
	}

	public function getBoundOrigin() : Vector3{
		return new Vector3($this->boundX, $this->boundY, $this->boundZ);
	}

	public function setBound(int $boundX, int $boundY, int $boundZ) : self{
		$this->hasBoundOrigin = true;
		$this->boundX = $boundX;
		$this->boundY = $boundY;
		$this->boundZ = $boundZ;
		return $this;
	}

	public function isInRaid() : bool{
		return $this->isInRaid;
	}

	public function setIsInRaid(bool $isInRaid) : self{
		$this->isInRaid = $isInRaid;
		return $this;
	}

	public function isReactToBell() : bool{
		return $this->reactToBell;
	}

	public function setReactToBell(bool $reactToBell) : self{
		$this->reactToBell = $reactToBell;
		return $this;
	}

	protected function doVanillaMobTick(int $tickDiff = 1) : bool{
		$hasUpdate = $this->doVanillaEntityTick($tickDiff);
		if($this->isAlive()){
			// find block of interest
			// pathfinding logic
			// move logic
			// attack logic
			// interact logic
			// leash logic
			// ride logic
			// jump logic
			// despawn logic
			// spawn logic
			// breed logic
			// tame logic
			// follow owner logic
		}
		return $hasUpdate;
	}

}
