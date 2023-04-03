<?php

declare(strict_types=1);

namespace jasonwynn10\VanillaEntityAI\entity\trait;

use jasonwynn10\VanillaEntityAI\util\Utils;
use pocketmine\block\Air;
use pocketmine\block\Block;
use pocketmine\block\Grass;
use pocketmine\block\Opaque;
use pocketmine\math\Facing;
use pocketmine\world\Position;
use function count;
use function mt_rand;

trait AnimalPathfindingTrait{

	protected static function getBlockOfInterest(Position $center) : Block{
		// ramdomly select a block from a 21x21x15 cube centered around block below entity for 10 blocks of interest
		// block must be opaque and have air above it
		$totalWeight = 0;
		$cumulativeWeights = [];
		$blocksOfInterest = [];
		while(count($blocksOfInterest) < 10) {
			$blockPosition = $center->add(mt_rand(-10, 10), mt_rand(-7, 7), mt_rand(-10, 10));
			$block = $center->getWorld()->getBlockAt($blockPosition->x, $blockPosition->y, $blockPosition->z);
			$aboveBlock = $block->getSide(Facing::UP);
			$abovePosition = $aboveBlock->getPosition();
			if($block instanceof Opaque && $aboveBlock instanceof Air && $center->getWorld()->isInWorld($abovePosition->x, $abovePosition->y, $abovePosition->z)) {
				// score blocks of interest by grass = 10 and heuristic h = g - 0.5 where g = f / (4 - 3 * f) and f = light level / 15
				$h = 10;
				if($block instanceof Grass){
					$f = $block->getLightLevel() / 15.0;
					$h = ($f / (4.0 - 3.0 * $f)) - 0.5;
				}
				$totalWeight += $h;
				$cumulativeWeights[] = $totalWeight; // TODO: verify this is correct
				$blocksOfInterest[] = $block;
			}
		}
		// pick block using weighted random with binary search util method
		$index = Utils::binarySearchFloatArray($cumulativeWeights, mt_rand(-1, $totalWeight));
		return $blocksOfInterest[$index];
	}

}
