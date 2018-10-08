<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\passive;

use jasonwynn10\VanillaEntityAI\entity\AgeableTrait;
use jasonwynn10\VanillaEntityAI\entity\Collidable;
use jasonwynn10\VanillaEntityAI\entity\CollisionCheckingTrait;
use jasonwynn10\VanillaEntityAI\entity\Interactable;
use jasonwynn10\VanillaEntityAI\entity\passiveaggressive\Player;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\math\AxisAlignedBB;

class Villager extends \pocketmine\entity\Villager implements Collidable, Interactable {
	use AgeableTrait, CollisionCheckingTrait;

	/**
	 * @param Entity $entity
	 */
	public function onCollideWithEntity(Entity $entity) : void {
		// TODO: Implement onCollideWithEntity() method.
	}

	public function onCollideWithBlock(Block $block) : void {
		// TODO: Implement onCollideWithBlock() method.
	}

	/**
	 * @param AxisAlignedBB $source
	 */
	public function push(AxisAlignedBB $source) : void {
		// TODO: Implement push() method.
	}

	public function onPlayerInteract(Player $player) : void {
		// TODO: Implement onPlayerInteract() method.
	}
}