<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\network;

use jasonwynn10\VanillaEntityAI\entity\passiveaggressive\Player;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\PlayerInputPacket;
use pocketmine\Server;
use pocketmine\timings\Timings;

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

	public function handleDataPacket(DataPacket $packet){
		$timings = Timings::getReceiveDataPacketTimings($packet);
		$timings->startTiming();

		$packet->decode();
		var_dump($packet); // TODO: delete
		if(!$packet->feof() and !$packet->mayHaveUnreadBytes()){
			$remains = substr($packet->buffer, $packet->offset);
			$this->server->getLogger()->debug("Still " . strlen($remains) . " bytes unread in " . $packet->getName() . ": 0x" . bin2hex($remains));
		}

		$this->server->getPluginManager()->callEvent($ev = new DataPacketReceiveEvent($this->player, $packet));
		if(!$ev->isCancelled() and !$packet->handle($this)){
			$this->server->getLogger()->debug("Unhandled " . $packet->getName() . " received from " . $this->player->getName() . ": 0x" . bin2hex($packet->buffer));
		}

		$timings->stopTiming();
	}

	public function handlePlayerInput(PlayerInputPacket $packet) : bool {
		return $this->player->handlePlayerInput($packet);
	}
}