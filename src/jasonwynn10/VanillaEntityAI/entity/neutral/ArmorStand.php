<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\neutral;

use pocketmine\entity\Entity;

class ArmorStand extends Entity {
	public function initEntity(): void {
		parent::initEntity(); // TODO: Change the autogenerated stub
	}

	/**
	 * @param int $tickDiff
	 *
	 * @return bool
	 */
	public function entityBaseTick(int $tickDiff = 1): bool {
		return parent::entityBaseTick($tickDiff); // TODO: Change the autogenerated stub
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return "Armor Stand";
	}
}