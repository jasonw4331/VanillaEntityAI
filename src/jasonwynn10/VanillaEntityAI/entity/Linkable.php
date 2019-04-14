<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity;

use pocketmine\entity\Entity;

interface Linkable {
	/**
	 * @return Entity|Linkable|null
	 */
	public function getLink() : ?Linkable;

	/**
	 * @param Linkable|null $entity
	 *
	 * @return Linkable
	 */
	public function setLink(?Linkable $entity) : self;

	/**
	 * @return bool
	 */
	public function unlink() : bool;
}