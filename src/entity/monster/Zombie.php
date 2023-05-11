<?php
declare(strict_types=1);

namespace jasonw4331\VanillaEntityAI\entity\monster;

use jasonw4331\VanillaEntityAI\entity\interfaces\Hostile;
use jasonw4331\VanillaEntityAI\entity\trait\ArmorWearerTrait;
use jasonw4331\VanillaEntityAI\entity\trait\ItemHolderTrait;
use jasonw4331\VanillaEntityAI\entity\trait\VanillaMobTrait;

class Zombie extends \pocketmine\entity\Zombie implements Hostile{
	use VanillaMobTrait;
	use ItemHolderTrait;
	use ArmorWearerTrait;

}