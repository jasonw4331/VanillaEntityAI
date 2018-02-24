<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI;

use jasonwynn10\VanillaEntityAI\entity\hostile\Zombie;
use jasonwynn10\VanillaEntityAI\entity\passive\Pig;
use pocketmine\entity\Creature;
use pocketmine\entity\Effect;
use pocketmine\entity\Entity;
use pocketmine\entity\Monster;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use spoondetector\SpoonDetector;

class EntityAI extends PluginBase {
	public static function passiveAI(Creature $entity, int $tickDiff = 1) : Vector3 {
		$return = $entity->asVector3();
		if($entity->isInsideOfWater() and !$entity->hasEffect(Effect::WATER_BREATHING)) {
			$airTicks = $entity->getDataPropertyManager()->getPropertyValue(Entity::DATA_AIR, Entity::DATA_TYPE_SHORT) - $tickDiff;
			if($airTicks <= -20) {
				$airTicks = 0;
				$ev = new EntityDamageEvent($entity, EntityDamageEvent::CAUSE_DROWNING, 2);
				$entity->attack($ev);
			}
			$entity->getDataPropertyManager()->setPropertyValue(Entity::DATA_AIR, Entity::DATA_TYPE_SHORT, $airTicks);
		}else{
			$entity->getDataPropertyManager()->setPropertyValue(Entity::DATA_AIR, Entity::DATA_TYPE_SHORT, 300);
		}
		return $return;
	}

	public static function hostileAI(Monster $entity, int $tickDiff = 1) : Vector3 {
		$return = $entity->asVector3();
		if($entity->isInsideOfWater() and !$entity->hasEffect(Effect::WATER_BREATHING)) {
			$airTicks = $entity->getDataPropertyManager()->getPropertyValue(Entity::DATA_AIR, Entity::DATA_TYPE_SHORT) - $tickDiff;
			if($airTicks <= -20) {
				$airTicks = 0;
				$ev = new EntityDamageEvent($entity, EntityDamageEvent::CAUSE_DROWNING, 2);
				$entity->attack($ev);
			}
			$entity->getDataPropertyManager()->setPropertyValue(Entity::DATA_AIR, Entity::DATA_TYPE_SHORT, $airTicks);
		}else{
			$entity->getDataPropertyManager()->setPropertyValue(Entity::DATA_AIR, Entity::DATA_TYPE_SHORT, 300);
		}
		return $return;
	}

	public static function passiveAggressiveAI(Creature $entity, int $tickDiff = 1) : Vector3 {
		$return = $entity->asVector3();
		if($entity->isInsideOfWater() and !$entity->hasEffect(Effect::WATER_BREATHING)) {
			$airTicks = $entity->getDataPropertyManager()->getPropertyValue(Entity::DATA_AIR, Entity::DATA_TYPE_SHORT) - $tickDiff;
			if($airTicks <= -20) {
				$airTicks = 0;
				$ev = new EntityDamageEvent($entity, EntityDamageEvent::CAUSE_DROWNING, 2);
				$entity->attack($ev);
			}
			$entity->getDataPropertyManager()->setPropertyValue(Entity::DATA_AIR, Entity::DATA_TYPE_SHORT, $airTicks);
		}else{
			$entity->getDataPropertyManager()->setPropertyValue(Entity::DATA_AIR, Entity::DATA_TYPE_SHORT, 300);
		}
		return $return;
	}

	public function onLoad() : void {
		//hostile
		Entity::registerEntity(Zombie::class, true, ['Zombie', 'minecraft:zombie']);
		//passive
		Entity::registerEntity(Pig::class, true, ['Pig', 'minecraft:pig']);
		//PassiveAgressive
		//neutral
	}

	public function onEnable() : void {
		SpoonDetector::printSpoon($this, "spoon.txt");
		new EntityListener($this);
	}
}