<?php

declare(strict_types=1);

namespace jasonw4331\VanillaEntityAI\entity\trait;

use jasonw4331\VanillaEntityAI\util\Utils;
use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;

trait VanillaEntityTrait{
	private string $identifier = "";
	private int $uniqueId = 0;
	private ?int $lastDimensionId = null;
	private bool $invulnerable = false;
	private int $portalCooldown = 0;
	private bool $isGlobal = false;
	private bool $isAutonomous = false;
	private ?int $linkEntityId = 0;
	private ?int $linkId = 0;
	private bool $lootDropped = false;
	private int $color = 0;
	private int $color2 = 0;
	private int $strength = 0;
	private int $strengthMax = 0;
	private bool $sheared = false;
	private bool $isIllagerCaptain = false;
	private int $ownerNew = -1;
	private bool $sitting = false;
	private bool $isBaby = false;
	private bool $isTamed = false;
	private bool $isTrusting = false;
	private bool $isOrphaned = false;
	private bool $isAngry = false;
	private bool $isOutOfControl = false;
	private int $variant = 0;
	private int $markVariant = 0;
	private bool $saddled = false;
	private bool $chested = false;
	private bool $showBottom = false;
	private bool $isEating = false;
	private bool $isScared = false;
	private bool $isStunned = false;
	private bool $isRoaring = false;
	private int $skinId = 0;
	private bool $persistent = false;

	public function saveEntityNBT(CompoundTag $nbt) : CompoundTag{
		$nbt->setString('identifier', $this->identifier); // the namespaced identifier of this entity
		$nbt->setLong('UniqueID', $this->uniqueId); // UUID
		// position set in Entity::saveNBT()
		// motion set in Entity::saveNBT()
		// rotation set in Entity::saveNBT()
		// CustomName set in Entity::saveNBT()
		// CustomNameVisible set in Entity::saveNBT()
		if($this->lastDimensionId !== null){
			$nbt->setInt('LastDimensionId', $this->lastDimensionId); // may not exist // unknown
		}
		// FallDistance set in Entity::saveNBT()
		// Fire set in Entity::saveNBT()
		// onGround set in Entity::saveNBT()
		$nbt->setByte('Invulnerable', $this->invulnerable ? 1 : 0);
		$nbt->setInt('PortalCooldown', $this->portalCooldown);
		$nbt->setByte('IsGlobal', $this->isGlobal ? 1 : 0); // lightning bolt, ender dragon, arrow
		$nbt->setByte('IsAutonomous', $this->isAutonomous ? 1 : 0); // unknown
		if($this->linkEntityId !== null && $this->linkId !== null) {
			$nbt->setTag('LinksTag', CompoundTag::create()
				->setLong('entityID', $this->linkEntityId) // UUID of linked entity
				->setInt('LinkID', $this->linkId) // unknown
			);
		}
		$nbt->setByte('LootDropped', $this->lootDropped ? 1 : 0);
		$nbt->setByte('Color', $this->color); //
		$nbt->setByte('Color2', $this->color2);
		$nbt->setInt('Strength', $this->strength); // llama inventory size = 3 x strength
		$nbt->setInt('StrengthMax', $this->strengthMax); // llama max inventory size = 3 x strengthMax
		$nbt->setByte('Sheared', $this->sheared ? 1 : 0);
		$nbt->setByte('IsIllagerCaptain', $this->isIllagerCaptain ? 1 : 0);
		$nbt->setLong('OwnerNew', $this->ownerNew); // unknown // defaults to -1
		$nbt->setByte('Sitting', $this->sitting ? 1 : 0);
		$nbt->setByte('IsBaby', $this->isBaby ? 1 : 0);
		$nbt->setByte('IsTamed', $this->isTamed ? 1 : 0);
		$nbt->setByte('IsTrusting', $this->isTrusting ? 1 : 0); // Used by fox and ocelot. Defaults to 0.
		$nbt->setByte('IsOrphaned', $this->isOrphaned ? 1 : 0); // if this entity is not spawn from its parents. Used by all the mobs that can breed.
		$nbt->setByte('IsAngry', $this->isAngry ? 1 : 0);
		$nbt->setByte('IsOutOfControl', $this->isOutOfControl ? 1 : 0); // used by boats
		$nbt->setInt('Variant', $this->variant);
		$nbt->setInt('MarkVariant', $this->markVariant);
		$nbt->setByte('Saddled', $this->saddled ? 1 : 0);
		$nbt->setByte('Chested', $this->chested ? 1 : 0);
		$nbt->setByte('ShowBottom', $this->showBottom ? 1 : 0); // if the End Crystal shows the bedrock slate underneath\
		$nbt->setByte('IsGliding', $this->gliding ? 1 : 0);
		$nbt->setByte('IsSwimming', $this->swimming ? 1 : 0);
		$nbt->setByte('IsEating', $this->isEating ? 1 : 0);
		$nbt->setByte('IsScared', $this->isScared ? 1 : 0);
		$nbt->setByte('IsStunned', $this->isStunned ? 1 : 0); // used by Ravager
		$nbt->setByte('IsRoaring', $this->isRoaring ? 1 : 0); // used by Ravager
		$nbt->setInt('SkinID', $this->skinId); // used by Villager and Zombie Villager
		$nbt->setByte('Persistent', $this->persistent ? 1 : 0); // do not despawn and do not count toward the mob cap

		return $nbt;
	}

	public function isColliding(Block $block) : bool {
		return $block->collidesWithBB($this->getBoundingBox());
	}

	public function getTeamColor() : int{
		$team = $this->getTeam();
		return $team != null && $team->getColor()->getColor() != null ? $team->getColor()->getColor() : 16777215;
	}

	public function isSpectator() : bool {
		return false;
	}

	public final function unRide() : void{
		if($this->isVehicle()) {
			$this->ejectPassengers();
		}

		if($this->isPassenger()) {
			$this->stopRiding();
		}
	}

	public final function discard() : void {
		$this->flagForDespawn();
	}

	public function closerThan(Vector3 $pos, float $horizontalDistance, float $verticalDistance = null) : bool {
		if($verticalDistance !== null) {
			$d0 = $pos->x - $this->getPosition()->x;
			$d1 = $pos->y - $this->getPosition()->y;
			$d2 = $pos->z - $this->getPosition()->z;
			return $d0 * $d0 + $d2 * $d2 < $horizontalDistance * $horizontalDistance && $d1 * $d1 < $verticalDistance * $verticalDistance;
		}
		return $this->getPosition()->distanceSquared($pos) < $horizontalDistance * $horizontalDistance;
	}

	public function turn(float $yaw, float $pitch) : void {
		$f = $yaw * 0.15;
		$f1 = $pitch * 0.15;
		$this->setRotation($this->location->yaw + $f, $this->location->pitch + $f1);
		$this->setRotation(Utils::clamp($this->location->yaw, -90, 90), $this->location->pitch);
		$this->xRotO += $f;
		$this->yRotO += $f1;
		$this->xRotO = Utils::clamp($this->xRotO, -90, 90);
		if($this->vehicle != null) {
			$this->vehicle->onPassengerTurned($this);
		}
	}

	public function getIdentifier() : string{
		return $this->identifier;
	}

	public function setIdentifier(string $identifier) : self{
		$this->identifier = $identifier;
		return $this;
	}

	public function getUniqueId() : int{
		return $this->uniqueId;
	}

	public function setUniqueId(int $uniqueId) : self{
		$this->uniqueId = $uniqueId;
		return $this;
	}

	public function getLastDimensionId() : ?int{
		return $this->lastDimensionId;
	}

	public function setLastDimensionId(?int $lastDimensionId) : self{
		$this->lastDimensionId = $lastDimensionId;
		return $this;
	}

	public function isInvulnerable() : bool{
		return $this->invulnerable;
	}

	public function setInvulnerable(bool $invulnerable) : self{
		$this->invulnerable = $invulnerable;
		return $this;
	}

	public function getPortalCooldown() : int{
		return $this->portalCooldown;
	}

	public function setPortalCooldown(int $portalCooldown) : self{
		$this->portalCooldown = $portalCooldown;
		return $this;
	}

	public function isGlobal() : bool{
		return $this->isGlobal;
	}

	public function setIsGlobal(bool $isGlobal) : self{
		$this->isGlobal = $isGlobal;
		return $this;
	}

	public function isAutonomous() : bool{
		return $this->isAutonomous;
	}

	public function setIsAutonomous(bool $isAutonomous) : self{
		$this->isAutonomous = $isAutonomous;
		return $this;
	}

	public function createLink(int $entityUniqueId, int $linkId) : self{
		$this->linkEntityId = $entityUniqueId;
		$this->linkId = $linkId;
		return $this;
	}

	public function isLootDropped() : bool{
		return $this->lootDropped;
	}

	public function setLootDropped(bool $lootDropped) : self{
		$this->lootDropped = $lootDropped;
		return $this;
	}

	public function getColor() : int{
		return $this->color;
	}

	public function setColor(int $color) : self{
		$this->color = $color;
		return $this;
	}

	public function getColor2() : int{
		return $this->color2;
	}

	public function setColor2(int $color2) : self{
		$this->color2 = $color2;
		return $this;
	}

	public function getStrength() : int{
		return $this->strength;
	}

	public function setStrength(int $strength) : self{
		$this->strength = $strength;
		return $this;
	}

	public function getStrengthMax() : int{
		return $this->strengthMax;
	}

	public function setStrengthMax(int $strengthMax) : self{
		$this->strengthMax = $strengthMax;
		return $this;
	}

	public function isSheared() : bool{
		return $this->sheared;
	}

	public function setSheared(bool $sheared) : self{
		$this->sheared = $sheared;
		return $this;
	}

	public function isIllagerCaptain() : bool{
		return $this->isIllagerCaptain;
	}

	public function setIsIllagerCaptain(bool $isIllagerCaptain) : self{
		$this->isIllagerCaptain = $isIllagerCaptain;
		return $this;
	}

	public function getOwnerNew() : int{
		return $this->ownerNew;
	}

	public function setOwnerNew(int $ownerNew) : self{
		$this->ownerNew = $ownerNew;
		return $this;
	}

	public function isSitting() : bool{
		return $this->sitting;
	}

	public function setSitting(bool $sitting) : self{
		$this->sitting = $sitting;
		return $this;
	}

	public function isBaby() : bool{
		return $this->isBaby;
	}

	public function setIsBaby(bool $isBaby) : self{
		if($this->isBaby !== $isBaby)
			$this->setScale($isBaby ? 0.5 : 1);
		$this->isBaby = $isBaby;
		return $this;
	}

	public function isTamed() : bool{
		return $this->isTamed;
	}

	public function setIsTamed(bool $isTamed) : self{
		$this->isTamed = $isTamed;
		return $this;
	}

	public function isTrusting() : bool{
		return $this->isTrusting;
	}

	public function setIsTrusting(bool $isTrusting) : self{
		$this->isTrusting = $isTrusting;
		return $this;
	}

	public function isOrphaned() : bool{
		return $this->isOrphaned;
	}

	public function setIsOrphaned(bool $isOrphaned) : self{
		$this->isOrphaned = $isOrphaned;
		return $this;
	}

	public function isAngry() : bool{
		return $this->isAngry;
	}

	public function setIsAngry(bool $isAngry) : self{
		$this->isAngry = $isAngry;
		return $this;
	}

	public function isOutOfControl() : bool{
		return $this->isOutOfControl;
	}

	public function setIsOutOfControl(bool $isOutOfControl) : self{
		$this->isOutOfControl = $isOutOfControl;
		return $this;
	}

	public function getVariant() : int{
		return $this->variant;
	}

	public function setVariant(int $variant) : self{
		$this->variant = $variant;
		return $this;
	}

	public function getMarkVariant() : int{
		return $this->markVariant;
	}

	public function setMarkVariant(int $markVariant) : self{
		$this->markVariant = $markVariant;
		return $this;
	}

	public function isSaddled() : bool{
		return $this->saddled;
	}

	public function setSaddled(bool $saddled) : self{
		$this->saddled = $saddled;
		return $this;
	}

	public function isChested() : bool{
		return $this->chested;
	}

	public function setChested(bool $chested) : self{
		$this->chested = $chested;
		return $this;
	}

	public function isShowBottom() : bool{
		return $this->showBottom;
	}

	public function setShowBottom(bool $showBottom) : self{
		$this->showBottom = $showBottom;
		return $this;
	}

	public function isEating() : bool{
		return $this->isEating;
	}

	public function setIsEating(bool $isEating) : self{
		$this->isEating = $isEating;
		return $this;
	}

	public function isScared() : bool{
		return $this->isScared;
	}

	public function setIsScared(bool $isScared) : self{
		$this->isScared = $isScared;
		return $this;
	}

	public function isStunned() : bool{
		return $this->isStunned;
	}

	public function setIsStunned(bool $isStunned) : self{
		$this->isStunned = $isStunned;
		return $this;
	}

	public function isRoaring() : bool{
		return $this->isRoaring;
	}

	public function setIsRoaring(bool $isRoaring) : self{
		$this->isRoaring = $isRoaring;
		return $this;
	}

	public function getSkinId() : int{
		return $this->skinId;
	}

	public function setSkinId(int $skinId) : self{
		$this->skinId = $skinId;
		return $this;
	}

	public function isPersistent() : bool{
		return $this->persistent;
	}

	public function setPersistent(bool $persistent) : self{
		$this->persistent = $persistent;
		return $this;
	}

	protected function doVanillaEntityTick(int $tickDiff = 1) : bool{
		$hasUpdate = false;

		if ($this->isPassenger() && $this->getVehicle()->isRemoved()) {
			$this->stopRiding();
		}

		if ($this->boardingCooldown > 0) {
			--$this->boardingCooldown;
		}

		$this->walkDistO = $this->walkDist;
		$this->xRotO = $this->getXRot();
		$this->yRotO = $this->getYRot();
		$this->handleNetherPortal();
		if ($this->canSpawnSprintParticle()) {
			$this->spawnSprintParticle();
		}

		$this->wasInPowderSnow = $this->isInPowderSnow;
		$this->isInPowderSnow = false;
		$this->updateInWaterStateAndDoFluidPushing();
		$this->updateFluidOnEyes();
		$this->updateSwimming();
		if ($this->level->isClientSide) {
			$this->clearFire();
		} else if ($this->remainingFireTicks > 0) {
			if ($this->fireImmune()) {
				$this->setRemainingFireTicks($this->remainingFireTicks - 4);
				if ($this->remainingFireTicks < 0) {
					$this->clearFire();
				}
			} else {
				if ($this->remainingFireTicks % 20 == 0 && !$this->isInLava()) {
					$this->hurt(DamageSource::ON_FIRE(), 1.0);
				}

				$this->setRemainingFireTicks($this->remainingFireTicks - 1);
			}

			if ($this->getTicksFrozen() > 0) {
				$this->setTicksFrozen(0);
				$this->level->levelEvent(null, 1009, $this->blockPosition, 1);
			}
		}

		if ($this->isInLava()) {
			$this->lavaHurt();
			$this->fallDistance *= $this->getFluidFallDistanceModifier(ForgeMod::LAVA_TYPE()->get());
		}

		$this->checkOutOfWorld();
		if (!$this->level->isClientSide) {
			$this->setSharedFlagOnFire($this->remainingFireTicks > 0);
		}

		$this->firstTick = false;

		return $hasUpdate;
	}

}
