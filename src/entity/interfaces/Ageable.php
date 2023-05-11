<?php
declare(strict_types=1);

namespace jasonw4331\VanillaEntityAI\entity\interfaces;

interface Ageable{

	public CONST BABY_TICKS = -24000; // 20 minutes before adulthood
	public CONST RESET_AGE_TICKS = 6000; // 5 minutes into adulthood

	public function getAge() : int;

	public function setAge(int $age) : self;
}