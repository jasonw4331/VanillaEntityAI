<?php
declare(strict_types=1);

namespace jasonw4331\VanillaEntityAI\entity\trait;

use pocketmine\nbt\tag\CompoundTag;

trait BreedableTrait{
	private int $inLove = 0;
	private int $loveCause = 0;
	private int $breedCooldown = 0;

	protected function saveBreedableNBT(CompoundTag $nbt) : CompoundTag {
		$nbt->setInt("InLove", $this->inLove); // ticks until loses breedable status
		$nbt->setLong("LoveCause", $this->loveCause); // UniqueId of player who caused this entity to be in love
		$nbt->setInt("BreedCooldown", $this->breedCooldown); // ticks until can breed again
		return $nbt;
	}

	public function getInLove() : int{
		return $this->inLove;
	}

	public function setInLove(int $inLove) : self{
		$this->inLove = $inLove;
		return $this;
	}

	public function getLoveCause() : int{
		return $this->loveCause;
	}

	public function setLoveCause(int $loveCause) : self{
		$this->loveCause = $loveCause;
		return $this;
	}

	public function getBreedCooldown() : int{
		return $this->breedCooldown;
	}

	public function setBreedCooldown(int $breedCooldown) : self{
		$this->breedCooldown = $breedCooldown;
		return $this;
	}

	protected function doBreedableTick(int $tickDiff = 1) : bool{
		$hasUpdate = false;
		if(!$this->isAlive())
			return false;

		if($this->inLove > 0) {
			$this->inLove -= $tickDiff;
			if($this->inLove <= 0) {
				$this->inLove = 0;
				$this->loveCause = 0;
				$hasUpdate = true;
			}
		}
		if($this->breedCooldown > 0) {
			$this->breedCooldown -= $tickDiff;
			if($this->breedCooldown <= 0) {
				$this->breedCooldown = 0;
				$hasUpdate = true;
			}
		}
		return $hasUpdate;
	}
}