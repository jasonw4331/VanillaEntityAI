<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\hostile;

class Stray extends Skeleton {
	public const NETWORK_ID = self::STRAY;

	/**
	 * @return string
	 */
	public function getName() : string {
		return "Stray";
	}
}