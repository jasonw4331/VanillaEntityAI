<?php
declare(strict_types=1);

namespace jasonw4331\VanillaEntityAI\entity\interfaces;

use pocketmine\math\Vector3;

interface VanillaMob extends VanillaEntity{

	public function getLeashHolderId() : int;

	public function setLeashHolderId(int $leashHolderId) : self;

	public function getHurtTime() : int;

	public function setHurtTime(int $hurtTime) : self;

	public function getDeathTime() : int;

	public function setDeathTime(int $deathTime) : self;

	public function isSurfaceMob() : bool;

	public function setIsSurfaceMob(bool $isSurfaceMob) : self;

	public function isNaturalSpawn() : bool;

	public function setNaturalSpawn(bool $naturalSpawn) : self;

	public function getBoundOrigin() : Vector3;

	public function setBound(int $boundX, int $boundY, int $boundZ) : self;

	public function isInRaid() : bool;

	public function setIsInRaid(bool $isInRaid) : self;

	public function isReactToBell() : bool;

	public function setReactToBell(bool $reactToBell) : self;

}