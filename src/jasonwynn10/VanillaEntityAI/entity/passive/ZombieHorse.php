<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\passive;
class ZombieHorse extends Horse {
	public const NETWORK_ID = self::ZOMBIE_HORSE;
	public $width = 1.3;
	public $height = 1.5;

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
	 * @return array
	 */
	public function getDrops(): array {
		return parent::getDrops(); // TODO: Change the autogenerated stub
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return "Zombie Horse";
	}
}