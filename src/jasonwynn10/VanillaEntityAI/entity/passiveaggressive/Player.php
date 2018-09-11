<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity\passiveaggressive;

use jasonwynn10\VanillaEntityAI\network\PlayerNetworkSessionAdapter;
use pocketmine\network\mcpe\protocol\PlayerInputPacket;
use pocketmine\network\SourceInterface;

class Player extends \pocketmine\Player {
	/**
	 * @param SourceInterface $interface
	 * @param string          $ip
	 * @param int             $port
	 */
	public function __construct(SourceInterface $interface, string $ip, int $port){
		parent::__construct($interface, $ip, $port);
		$this->sessionAdapter = new PlayerNetworkSessionAdapter($this->server, $this);
	}

	/**
	 * @param PlayerInputPacket $packet
	 *
	 * @return bool
	 */
	public function handlePlayerInput(PlayerInputPacket $packet) : bool {
		return false;
	}

}