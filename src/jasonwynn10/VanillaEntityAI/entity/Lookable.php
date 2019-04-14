<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity;

use jasonwynn10\VanillaEntityAI\entity\passiveaggressive\Player;

interface Lookable {

	/**
	 * @param Player $player
	 */
	public function onPlayerLook(Player $player) : void;
}