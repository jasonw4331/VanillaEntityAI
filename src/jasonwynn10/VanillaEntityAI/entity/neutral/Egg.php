<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\neutral;

use jasonwynn10\VanillaEntityAI\entity\passive\Chicken;
use pocketmine\event\entity\ProjectileHitEvent;

class Egg extends \pocketmine\entity\projectile\Egg {

	protected function onHit(ProjectileHitEvent $event) : void{
		parent::onHit($event);
		if(mt_rand(1, 8) === 1) {
			/** @var Chicken $chicken */
			$chicken = Chicken::createEntity("Chicken", $this->level, Chicken::createBaseNBT($this));
			if($chicken !== null) {
				$chicken->setBaby(true);
				$this->level->addEntity($chicken);
				$chicken->spawnToAll();
			}
			if(mt_rand(1,32) === 1) {
				$chicken = Chicken::createEntity("Chicken", $this->level, Chicken::createBaseNBT($this));
				if($chicken !== null) {
					$chicken->setBaby(true);
					$this->level->addEntity($chicken);
					$chicken->spawnToAll();
				}
				$chicken = Chicken::createEntity("Chicken", $this->level, Chicken::createBaseNBT($this));
				if($chicken !== null) {
					$chicken->setBaby(true);
					$this->level->addEntity($chicken);
					$chicken->spawnToAll();
				}
				$chicken = Chicken::createEntity("Chicken", $this->level, Chicken::createBaseNBT($this));
				if($chicken !== null) {
					$chicken->setBaby(true);
					$this->level->addEntity($chicken);
					$chicken->spawnToAll();
				}
			}
		}
	}
}