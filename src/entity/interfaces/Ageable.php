<?php
declare(strict_types=1);

namespace jasonwynn10\VanillaEntityAI\entity\interfaces;

interface Ageable{

	public function getAge() : int;

	public function setAge(int $age) : self;
}