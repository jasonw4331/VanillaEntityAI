<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\hostile;

class Drowned extends Zombie {
	public const NETWORK_ID = self::DROWNED;

	protected function applyGravity() : void {
		if(!$this->isUnderwater()) {
			parent::applyGravity();
		}
	}
}