<?php
declare(strict_types=1);

namespace jasonw4331\VanillaEntityAI\util;

final class Utils{
	/**
	 * @param int[] $arr
	 */
	public static function binarySearchIntegerArray(array $arr, int $value) : int {
		$low = 0;
		$high = count($arr) - 1;
		while ($low <= $high) {
			$mid = (int) floor(($low + $high) / 2);
			if ($arr[$mid] < $value) {
				$low = $mid + 1;
			} elseif ($arr[$mid] > $value) {
				$high = $mid - 1;
			} else {
				return $mid;
			}
		}
		return $low;
	}

	/**
	 * @param float[] $arr
	 */
	public static function binarySearchFloatArray(array $arr, float $value) : int {
		$low = 0;
		$high = count($arr) - 1;
		while ($low <= $high) {
			$mid = (int) floor(($low + $high) / 2);
			if ($arr[$mid] < $value) {
				$low = $mid + 1;
			} elseif ($arr[$mid] > $value) {
				$high = $mid - 1;
			} else {
				return $mid;
			}
		}
		return $low;
	}

	public static function clamp(float $value, float $min, float $max) : float {
		return max($min, min($max, $value));
	}
}