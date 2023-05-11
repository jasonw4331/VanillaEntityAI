<?php
declare(strict_types=1);

namespace jasonw4331\VanillaEntityAI\entity\animal;

use jasonw4331\VanillaEntityAI\entity\interfaces\VanillaMob;
use jasonw4331\VanillaEntityAI\entity\trait\AgeableTrait;
use jasonw4331\VanillaEntityAI\entity\trait\AnimalPathfindingTrait;
use jasonw4331\VanillaEntityAI\entity\trait\BreedableTrait;
use jasonw4331\VanillaEntityAI\entity\trait\VanillaMobTrait;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Living;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Pig extends Living implements VanillaMob{
	use VanillaMobTrait;
	use BreedableTrait;
	use AgeableTrait;
	use AnimalPathfindingTrait;

	public static function getNetworkTypeId() : string{ return EntityIds::PIG; }

	protected function getInitialSizeInfo() : EntitySizeInfo{
		return new EntitySizeInfo(0.9, 0.9);
	}

	public function getName() : string{
		return "Pig";
	}

	protected function initEntity(CompoundTag $nbt) : void{
		$this->setMaxHealth(10);
		parent::initEntity($nbt);
	}

	public function saveNBT() : CompoundTag{
		$nbt = parent::saveNBT();
		$nbt = $this->saveMobNBT($nbt);
		$nbt = $this->saveAgeableNBT($nbt);
		$nbt = $this->saveBreedableNBT($nbt);
		return $nbt;
	}

	protected function entityBaseTick(int $tickDiff = 1) : bool{
		$hasUpdate = parent::entityBaseTick($tickDiff);

		$hasUpdate = $this->doVanillaMobTick($tickDiff) ? true : $hasUpdate;
		$hasUpdate = $this->doAgeableTick($tickDiff) ? true : $hasUpdate;
		$hasUpdate = $this->doBreedableTick($tickDiff) ? true : $hasUpdate;

		return $hasUpdate;
	}
}