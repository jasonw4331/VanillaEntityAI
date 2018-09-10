<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\hostile;

class MagmaCube extends Slime implements CustomMonster {
	public const NETWORK_ID = self::MAGMA_CUBE;

	public $width = 1.2;
	public $height = 1.2;

	/**
	 * @return string
	 */
	public function getName() : string {
		return "Magma Cube";
	}
}