<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\hostile;

use jasonwynn10\VanillaEntityAI\entity\AgeableTrait;
use jasonwynn10\VanillaEntityAI\entity\ClimbingTrait;
use jasonwynn10\VanillaEntityAI\entity\ItemHolderTrait;

class Drowned extends Zombie {
	use ItemHolderTrait, AgeableTrait, ClimbingTrait;
	public const NETWORK_ID = self::DROWNED;

	protected function applyGravity() : void {
		if(!$this->isUnderwater()) {
			parent::applyGravity();
		}
	}
}