<?php
declare(strict_types=1);

namespace jasonw4331\VanillaEntityAI\command;

use CortexPE\Commando\args\BlockPositionArgument;
use CortexPE\Commando\args\FloatArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use jasonw4331\VanillaEntityAI\command\argument\EntityTypeArgument;
use jasonw4331\VanillaEntityAI\lang\CustomKnownTranslationFactory;
use jasonw4331\VanillaEntityAI\Main;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\entity\Location;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

final class SummonCommand extends BaseCommand {

	public function __construct(Main $plugin){
		parent::__construct(
			$plugin,
			"summon",
			CustomKnownTranslationFactory::summon_command_description()
		);
	}

	protected function prepare() : void{
		$this->registerArgument(0, new EntityTypeArgument('entityType', false));

		$this->registerArgument(1, new RawStringArgument('nameTag', false));
		$this->registerArgument(2, new BlockPositionArgument('spawnPos', true)); // block position to enable relative coordinates

		$this->registerArgument(1, new BlockPositionArgument('spawnPos', true)); // block position to enable relative coordinates
		$this->registerArgument(2, new FloatArgument('yRot', true));
		$this->registerArgument(3, new FloatArgument('xRot', true));
		$this->registerArgument(4, new RawStringArgument('spawnEvent', true));

		$this->addConstraint(new InGameRequiredConstraint($this));

		$this->setPermission('VanillaEntityAI.command.difficulty;pocketmine.command.summon');
	}

	/**
	 * @phpstan-param Player $sender
	 * @phpstan-param array{
	 *     entityType: class-string<Entity>,
	 *     nameTag?: string,
	 *     spawnPos?: Vector3,
	 *     yRot?: float,
	 *     xRot?: float,
	 *     spawnEvent?: string
	 * } $args
	 */
	public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void{
		if(isset($args['spawnPos'])){
			$spawnLocation = $args['spawnPos'];
		}
		if(isset($args['yRot']) || isset($args['xRot'])){
			$spawnLocation = Location::fromObject($spawnLocation, $sender->getWorld(), $args['yRot'] ?? 0, $args['xRot'] ?? 0);
		}

		// TODO: implement spawn event

		/** @var Entity $entity */
		$entity = new $args['entityType']($spawnLocation);

		if(isset($args['nameTag'])){
			$entity->setNameTag($args['nameTag']);
			$entity->setNameTagVisible(true);
		}

		$entity->spawnToAll();
		$sender->sendMessage("Object successfully spawned");
	}
}