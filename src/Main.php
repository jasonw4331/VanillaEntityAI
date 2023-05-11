<?php
declare(strict_types=1);

namespace jasonw4331\VanillaEntityAI;

use CortexPE\Commando\PacketHooker;
use jasonw4331\VanillaEntityAI\command\DifficultyCommand;
use jasonw4331\VanillaEntityAI\command\SummonCommand;
use jasonw4331\VanillaEntityAI\event\EventListener;
use jasonw4331\VanillaEntityAI\event\TaskCleanupListener;
use pocketmine\entity\EntityFactory;
use pocketmine\lang\Language;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use Symfony\Component\Filesystem\Path;

final class Main extends PluginBase{
	use SingletonTrait{
		reset as private;
		setInstance as private;
	}

	public function onLoad() : void{
		$this->setInstance($this);

		$this->registerEntities();
	}

	private function registerEntities() : void{
		$factory = EntityFactory::getInstance();
		// TODO: Register entities
	}

	public function onEnable() : void{
		PacketHooker::register($this);
		$this->getServer()->getCommandMap()->registerAll($this->getName(), [
			new DifficultyCommand($this),
			new SummonCommand($this),
		]);

		new EventListener($this);
		new TaskCleanupListener($this);

		$this->saveResource('/lang/config.yml', true);
		/** @var string[][] $contents */
		$contents = yaml_parse_file(Path::join($this->getDataFolder(), "lang", 'config.yml'));
		$languageAliases = [];
		foreach($contents as $language => $aliases){
			$mini = mb_strtolower($aliases['mini']);
			$this->saveResource('/lang/data/' . $mini . '.ini', true);
			$languageAliases[$mini] = $language;
		}

		$languages = [];
		$dir = scandir(Path::join($this->getDataFolder(), "lang", "data"));
		if($dir !== false){
			foreach($dir as $file){
				/** @phpstan-var array{dirname: string, basename: string, extension?: string, filename: string} $fileData */
				$fileData = pathinfo($file);
				if(!isset($fileData["extension"]) || $fileData["extension"] !== "ini"){
					continue;
				}
				$languageName = mb_strtolower($fileData["filename"]);
				$language = new Language(
					$languageName,
					Path::join($this->getDataFolder(), "lang", "data")
				);
				$languages[$languageName] = $language;
				foreach($languageAliases as $mini => $full){
					if(mb_strtolower($full) === $languageName){
						$languages[mb_strtolower($mini)] = $language;
						unset($languageAliases[$mini]);
					}
				}
			}
		}

		// add translations to existing server language instance
		$serverLanguage = $this->getServer()->getLanguage();
		$refClass = new \ReflectionClass($serverLanguage);
		$refPropA = $refClass->getProperty('lang');
		$refPropA->setAccessible(true);
		/** @var string[] $serverLanguageList */
		$serverLanguageList = $refPropA->getValue($serverLanguage);

		$pluginLanguage = $languages[$serverLanguage->getLang()];
		$refClass = new \ReflectionClass($pluginLanguage);
		$refPropB = $refClass->getProperty('lang');
		$refPropB->setAccessible(true);
		/** @var string[] $pluginLanguageList */
		$pluginLanguageList = $refPropB->getValue($pluginLanguage);

		$refPropA->setValue($serverLanguage, array_merge($serverLanguageList, $pluginLanguageList));
	}
}