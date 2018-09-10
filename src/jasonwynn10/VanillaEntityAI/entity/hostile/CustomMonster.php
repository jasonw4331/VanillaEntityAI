<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\hostile;

use pocketmine\level\Position;

interface CustomMonster {

	/**
	 * @return Position|null
	 */
	public function getTarget() : ?Position;

}