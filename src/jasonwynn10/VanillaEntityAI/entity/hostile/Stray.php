<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\hostile;
class Stray extends Skeleton {
	public const NETWORK_ID = self::STRAY;
	public $width = 0.875;
	public $height = 2.0;

	/**
	 * @return string
	 */
	public function getName(): string {
		return "Stray";
	}
}