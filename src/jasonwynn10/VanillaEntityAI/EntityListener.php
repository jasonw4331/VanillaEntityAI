<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI;

use pocketmine\event\Listener;

class EntityListener implements Listener {
	/** @var EntityAI $plugin */
	private $plugin;

	/**
	 * EntityListener constructor.
	 *
	 * @param EntityAI $plugin
	 */
	public function __construct(EntityAI $plugin) {
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
		$this->plugin = $plugin;
	}
}