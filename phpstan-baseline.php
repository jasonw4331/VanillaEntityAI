<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	'message' => '#^Dynamic call to static method jasonw4331\\\\VanillaEntityAI\\\\Main\\:\\:setInstance\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Main.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$args \\(array\\{difficulty\\: int\\}\\) of method jasonw4331\\\\VanillaEntityAI\\\\command\\\\DifficultyCommand\\:\\:onRun\\(\\) should be contravariant with parameter \\$args \\(array\\) of method CortexPE\\\\Commando\\\\BaseCommand\\:\\:onRun\\(\\)$#',
	'count' => 1,
	'path' => __DIR__ . '/src/command/DifficultyCommand.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$sender \\(pocketmine\\\\player\\\\Player\\) of method jasonw4331\\\\VanillaEntityAI\\\\command\\\\SummonCommand\\:\\:onRun\\(\\) should be contravariant with parameter \\$sender \\(pocketmine\\\\command\\\\CommandSender\\) of method CortexPE\\\\Commando\\\\BaseCommand\\:\\:onRun\\(\\)$#',
	'count' => 1,
	'path' => __DIR__ . '/src/command/SummonCommand.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$args \\(array\\{entityType\\: class\\-string\\<pocketmine\\\\entity\\\\Entity\\>, nameTag\\?\\: string, spawnPos\\?\\: pocketmine\\\\math\\\\Vector3, yRot\\?\\: float, xRot\\?\\: float, spawnEvent\\?\\: string\\}\\) of method jasonw4331\\\\VanillaEntityAI\\\\command\\\\SummonCommand\\:\\:onRun\\(\\) should be contravariant with parameter \\$args \\(array\\) of method CortexPE\\\\Commando\\\\BaseCommand\\:\\:onRun\\(\\)$#',
	'count' => 1,
	'path' => __DIR__ . '/src/command/SummonCommand.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$spawnLocation might not be defined\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/command/SummonCommand.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$x of static method pocketmine\\\\world\\\\World\\:\\:blockHash\\(\\) expects int, float\\|int given\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/data/BlockHueristicScoreCache.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$y of static method pocketmine\\\\world\\\\World\\:\\:blockHash\\(\\) expects int, float\\|int given\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/data/BlockHueristicScoreCache.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$z of static method pocketmine\\\\world\\\\World\\:\\:blockHash\\(\\) expects int, float\\|int given\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/data/BlockHueristicScoreCache.php',
];
$ignoreErrors[] = [
	'message' => '#^Using nullsafe method call on non\\-nullable type jasonw4331\\\\VanillaEntityAI\\\\task\\\\NaturalAnimalSpawnTask\\. Use \\-\\> instead\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/NaturalSpawnTaskCollector.php',
];
$ignoreErrors[] = [
	'message' => '#^Using nullsafe method call on non\\-nullable type jasonw4331\\\\VanillaEntityAI\\\\task\\\\NaturalMonsterSpawnTask\\. Use \\-\\> instead\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/data/NaturalSpawnTaskCollector.php',
];
$ignoreErrors[] = [
	'message' => '#^Using nullsafe method call on non\\-nullable type jasonw4331\\\\VanillaEntityAI\\\\task\\\\SpawnerTask\\. Use \\-\\> instead\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/data/SpawnerTaskCollector.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:\\$blockPosition\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:\\$boardingCooldown\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:\\$firstTick\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:\\$isInPowderSnow\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:\\$level\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:\\$persistingRiches\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:\\$remainingFireTicks\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:\\$tradeExperience\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:\\$tradeTier\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:\\$vehicle\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:\\$walkDist\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:\\$walkDistO\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:\\$wasInPowderSnow\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:\\$xRotO\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:\\$yRotO\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:canSpawnSprintParticle\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:checkOutOfWorld\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:clearFire\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:ejectPassengers\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:fireImmune\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:getFluidFallDistanceModifier\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:getTeam\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:getTicksFrozen\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:getVehicle\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:getXRot\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:getYRot\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:handleNetherPortal\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:hurt\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:isInLava\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:isPassenger\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:isVehicle\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:lavaHurt\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:setRemainingFireTicks\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:setSharedFlagOnFire\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:setTicksFrozen\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:spawnSprintParticle\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:stopRiding\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:updateFluidOnEyes\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:updateInWaterStateAndDoFluidPushing\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\animal\\\\Pig\\:\\:updateSwimming\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method LAVA_TYPE\\(\\) on an unknown class jasonw4331\\\\VanillaEntityAI\\\\entity\\\\trait\\\\ForgeMod\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method ON_FIRE\\(\\) on an unknown class jasonw4331\\\\VanillaEntityAI\\\\entity\\\\trait\\\\DamageSource\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$x of method pocketmine\\\\world\\\\World\\:\\:getBlockAt\\(\\) expects int, float\\|int given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$x of method pocketmine\\\\world\\\\World\\:\\:isInWorld\\(\\) expects int, float\\|int given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$max of function mt_rand expects int, float\\|int\\<0, max\\> given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$y of method pocketmine\\\\world\\\\World\\:\\:getBlockAt\\(\\) expects int, float\\|int given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$y of method pocketmine\\\\world\\\\World\\:\\:isInWorld\\(\\) expects int, float\\|int given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$z of method pocketmine\\\\world\\\\World\\:\\:getBlockAt\\(\\) expects int, float\\|int given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$z of method pocketmine\\\\world\\\\World\\:\\:isInWorld\\(\\) expects int, float\\|int given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/animal/Pig.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:\\$blockPosition\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:\\$boardingCooldown\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:\\$firstTick\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:\\$isInPowderSnow\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:\\$level\\.$#',
	'count' => 3,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:\\$persistingRiches\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:\\$remainingFireTicks\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:\\$tradeExperience\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:\\$tradeTier\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:\\$vehicle\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:\\$walkDist\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:\\$walkDistO\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:\\$wasInPowderSnow\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:\\$xRotO\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Access to an undefined property jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:\\$yRotO\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:canSpawnSprintParticle\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:checkOutOfWorld\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:clearFire\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:ejectPassengers\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:fireImmune\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:getFluidFallDistanceModifier\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:getTeam\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:getTicksFrozen\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:getVehicle\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:getXRot\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:getYRot\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:handleNetherPortal\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:hurt\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:isInLava\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:isPassenger\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:isVehicle\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:lavaHurt\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:setRemainingFireTicks\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:setSharedFlagOnFire\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:setTicksFrozen\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:spawnSprintParticle\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:stopRiding\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:updateFluidOnEyes\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:updateInWaterStateAndDoFluidPushing\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method jasonw4331\\\\VanillaEntityAI\\\\entity\\\\monster\\\\Zombie\\:\\:updateSwimming\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined method pocketmine\\\\inventory\\\\SimpleInventory\\:\\:getHotbarSize\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method LAVA_TYPE\\(\\) on an unknown class jasonw4331\\\\VanillaEntityAI\\\\entity\\\\trait\\\\ForgeMod\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to static method ON_FIRE\\(\\) on an unknown class jasonw4331\\\\VanillaEntityAI\\\\entity\\\\trait\\\\DamageSource\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/entity/monster/Zombie.php',
];
$ignoreErrors[] = [
	'message' => '#^Only booleans are allowed in an if condition, mixed given\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/event/EventListener.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$haystack of function in_array expects array, mixed given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/event/EventListener.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$tileData of class jasonw4331\\\\VanillaEntityAI\\\\task\\\\SpawnerTask constructor expects array\\{entityTypeId\\: string, spawnPotentials\\?\\: pocketmine\\\\nbt\\\\tag\\\\ListTag, spawnData\\?\\: pocketmine\\\\nbt\\\\tag\\\\CompoundTag, displayEntityWidth\\: float, displayEntityHeight\\: float, displayEntityScale\\: float, spawnDelay\\: int, minSpawnDelay\\: int, \\.\\.\\.\\}, array\\<non\\-empty\\-string, mixed\\> given\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/event/EventListener.php',
];
$ignoreErrors[] = [
	'message' => '#^Strict comparison using \\=\\=\\= between pocketmine\\\\block\\\\tile\\\\MonsterSpawner and null will always evaluate to false\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/event/EventListener.php',
];
$ignoreErrors[] = [
	'message' => '#^Property jasonw4331\\\\VanillaEntityAI\\\\event\\\\TaskCleanupListener\\:\\:\\$plugin is never read, only written\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/event/TaskCleanupListener.php',
];
$ignoreErrors[] = [
	'message' => '#^Property jasonw4331\\\\VanillaEntityAI\\\\task\\\\NaturalAnimalSpawnTask\\:\\:\\$plugin is never read, only written\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/task/NaturalAnimalSpawnTask.php',
];
$ignoreErrors[] = [
	'message' => '#^Property jasonw4331\\\\VanillaEntityAI\\\\task\\\\NaturalMonsterSpawnTask\\:\\:\\$plugin is never read, only written\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/task/NaturalMonsterSpawnTask.php',
];
$ignoreErrors[] = [
	'message' => '#^Method jasonw4331\\\\VanillaEntityAI\\\\task\\\\SpawnerTask\\:\\:getRandomSpawnPotentials\\(\\) should return pocketmine\\\\nbt\\\\tag\\\\CompoundTag but returns pocketmine\\\\nbt\\\\tag\\\\Tag\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/task/SpawnerTask.php',
];
$ignoreErrors[] = [
	'message' => '#^Offset \'SpawnData\' on array\\{entityTypeId\\: string, spawnPotentials\\?\\: pocketmine\\\\nbt\\\\tag\\\\ListTag, spawnData\\?\\: pocketmine\\\\nbt\\\\tag\\\\CompoundTag, displayEntityWidth\\: float, displayEntityHeight\\: float, displayEntityScale\\: float, spawnDelay\\: int, minSpawnDelay\\: int, \\.\\.\\.\\} on left side of \\?\\? does not exist\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/task/SpawnerTask.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$entityClass of static method jasonw4331\\\\VanillaEntityAI\\\\util\\\\SpawnVerifier\\:\\:canSpawn\\(\\) expects class\\-string\\<pocketmine\\\\entity\\\\Entity\\>, class\\-string\\<pocketmine\\\\entity\\\\Entity\\>\\|null given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/task/SpawnerTask.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$items of method jasonw4331\\\\VanillaEntityAI\\\\task\\\\SpawnerTask\\:\\:getRandomSpawnPotentials\\(\\) expects iterable\\<pocketmine\\\\nbt\\\\tag\\\\CompoundTag\\>&pocketmine\\\\nbt\\\\tag\\\\ListTag, pocketmine\\\\nbt\\\\tag\\\\ListTag given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/task/SpawnerTask.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#3 \\$tileData of class jasonw4331\\\\VanillaEntityAI\\\\task\\\\SpawnerTask constructor expects array\\{entityTypeId\\: string, spawnPotentials\\?\\: pocketmine\\\\nbt\\\\tag\\\\ListTag, spawnData\\?\\: pocketmine\\\\nbt\\\\tag\\\\CompoundTag, displayEntityWidth\\: float, displayEntityHeight\\: float, displayEntityScale\\: float, spawnDelay\\: int, minSpawnDelay\\: int, \\.\\.\\.\\}, array\\{entityTypeId\\: string, spawnPotentials\\?\\: pocketmine\\\\nbt\\\\tag\\\\ListTag, spawnData\\?\\: pocketmine\\\\nbt\\\\tag\\\\CompoundTag\\|null, displayEntityWidth\\: float, displayEntityHeight\\: float, displayEntityScale\\: float, spawnDelay\\: int, minSpawnDelay\\: int, \\.\\.\\.\\} given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/task/SpawnerTask.php',
];
$ignoreErrors[] = [
	'message' => '#^Property jasonw4331\\\\VanillaEntityAI\\\\task\\\\SpawnerTask\\:\\:\\$tileData \\(array\\{entityTypeId\\: string, spawnPotentials\\?\\: pocketmine\\\\nbt\\\\tag\\\\ListTag, spawnData\\?\\: pocketmine\\\\nbt\\\\tag\\\\CompoundTag, displayEntityWidth\\: float, displayEntityHeight\\: float, displayEntityScale\\: float, spawnDelay\\: int, minSpawnDelay\\: int, \\.\\.\\.\\}\\) does not accept array\\{entityTypeId\\: string, spawnPotentials\\?\\: pocketmine\\\\nbt\\\\tag\\\\ListTag, spawnData\\: pocketmine\\\\nbt\\\\tag\\\\CompoundTag\\|null, displayEntityWidth\\: float, displayEntityHeight\\: float, displayEntityScale\\: float, spawnDelay\\: int, minSpawnDelay\\: int, \\.\\.\\.\\}\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/task/SpawnerTask.php',
];
$ignoreErrors[] = [
	'message' => '#^Call to an undefined static method jasonw4331\\\\VanillaEntityAI\\\\data\\\\InhabitedChunkTimeTracker\\:\\:getTimeAt\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/util/DifficultyFactor.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
