<?php
declare(strict_types=1);

namespace jasonw4331\VanillaEntityAI\entity\interfaces;

interface VanillaEntity{

	public function getIdentifier() : string;

	public function setIdentifier(string $identifier) : self;

	public function getUniqueId() : int;

	public function setUniqueId(int $uniqueId) : self;

	public function getLastDimensionId() : ?int;

	public function setLastDimensionId(?int $lastDimensionId) : self;

	public function isInvulnerable() : bool;

	public function setInvulnerable(bool $invulnerable) : self;

	public function getPortalCooldown() : int;

	public function setPortalCooldown(int $portalCooldown) : self;

	public function isGlobal() : bool;

	public function setIsGlobal(bool $isGlobal) : self;

	public function isAutonomous() : bool;

	public function setIsAutonomous(bool $isAutonomous) : self;

	public function createLink(int $entityUniqueId, int $linkId) : self;

	public function isLootDropped() : bool;

	public function setLootDropped(bool $lootDropped) : self;

	public function getColor() : int;

	public function setColor(int $color) : self;

	public function getColor2() : int;

	public function setColor2(int $color2) : self;

	public function getStrength() : int;

	public function setStrength(int $strength) : self;

	public function getStrengthMax() : int;

	public function setStrengthMax(int $strengthMax) : self;

	public function isSheared() : bool;

	public function setSheared(bool $sheared) : self;

	public function isIllagerCaptain() : bool;

	public function setIsIllagerCaptain(bool $isIllagerCaptain) : self;

	public function getOwnerNew() : int;

	public function setOwnerNew(int $ownerNew) : self;

	public function isSitting() : bool;

	public function setSitting(bool $sitting) : self;

	public function isBaby() : bool;

	public function setIsBaby(bool $isBaby) : self;

	public function isTamed() : bool;

	public function setIsTamed(bool $isTamed) : self;

	public function isTrusting() : bool;

	public function setIsTrusting(bool $isTrusting) : self;

	public function isOrphaned() : bool;

	public function setIsOrphaned(bool $isOrphaned) : self;

	public function isAngry() : bool;

	public function setIsAngry(bool $isAngry) : self;

	public function isOutOfControl() : bool;

	public function setIsOutOfControl(bool $isOutOfControl) : self;

	public function getVariant() : int;

	public function setVariant(int $variant) : self;

	public function getMarkVariant() : int;

	public function setMarkVariant(int $markVariant) : self;

	public function isSaddled() : bool;

	public function setSaddled(bool $saddled) : self;

	public function isChested() : bool;

	public function setChested(bool $chested) : self;

	public function isShowBottom() : bool;

	public function setShowBottom(bool $showBottom) : self;

	public function isEating() : bool;

	public function setIsEating(bool $isEating) : self;

	public function isScared() : bool;

	public function setIsScared(bool $isScared) : self;

	public function isStunned() : bool;

	public function setIsStunned(bool $isStunned) : self;

	public function isRoaring() : bool;

	public function setIsRoaring(bool $isRoaring) : self;

	public function getSkinId() : int;

	public function setSkinId(int $skinId) : self;

	public function isPersistent() : bool;

	public function setPersistent(bool $persistent) : self;

}