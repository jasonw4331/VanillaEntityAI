<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity;

use pocketmine\entity\Ageable;

abstract class AnimalBase extends CreatureBase implements Ageable {
	use AgeableTrait;
}