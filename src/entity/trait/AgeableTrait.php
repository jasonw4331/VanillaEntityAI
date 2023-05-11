<?php
declare(strict_types=1);

namespace jasonw4331\VanillaEntityAI\entity\trait;

use pocketmine\nbt\tag\CompoundTag;

trait AgeableTrait{
	private int $age = 0;

	protected function saveAgeableNBT(CompoundTag $nbt) : CompoundTag {
		$nbt->setInt("Age", $this->age); // negative is baby, positive is adult
		return $nbt;
	}

	public function getAge() : int{
		return $this->age;
	}

	public function setAge(int $age) : self{
		$this->age = $age;
		return $this;
	}

	protected function doAgeableTick(int $tickDiff = 1) : bool{
		$hasUpdate = false;
		if(!$this->isAlive() || $this->age >= 0)
			return false;

		$this->age += $tickDiff;
		if($this->age >= 0) {
			$this->age = 0;
			$this->setIsBaby(false);
			$hasUpdate = true;
		}
		return $hasUpdate;
	}

}