<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\passive;

class Squid extends \pocketmine\entity\Squid {
	public function getXpDropAmount() : int {
		$exp = parent::getXpDropAmount();
		if(!$this->isBaby()) {
			$exp += mt_rand(1, 3);
			return $exp;
		}
		return $exp;
	}
}