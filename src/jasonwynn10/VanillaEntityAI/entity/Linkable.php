<?php
declare(strict_types=1);
namespace jasonwynn10\VanillaEntityAI\entity;

interface Linkable {
	/**
	 * @return Linkable|null
	 */
	public function getLink() : ?Linkable;

	/**
	 * @param Linkable $entity
	 */
	public function setLink(Linkable $entity);
}