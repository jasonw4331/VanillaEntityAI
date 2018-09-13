<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\network;

use jasonwynn10\VanillaEntityAI\entity\passiveaggressive\Player;
use pocketmine\network\mcpe\protocol\PlayerInputPacket;
use pocketmine\Server;

class PlayerNetworkSessionAdapter extends \pocketmine\network\mcpe\PlayerNetworkSessionAdapter {
	/** @var Server */
	protected $server;
	/** @var Player */
	protected $player;

	public function __construct(Server $server, Player $player) {
		parent::__construct($server, $player);
		$this->server = $server;
		$this->player = $player;
	}

	public function handlePlayerInput(PlayerInputPacket $packet): bool {
		return $this->player->handlePlayerInput($packet);
	}
}