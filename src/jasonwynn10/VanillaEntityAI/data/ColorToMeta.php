<?php
declare(strict_types=1);

namespace jasonwynn10\VanillaEntityAI\data;


class ColorToMeta {

	public const WHITE = 0;
	public const ORANGE = 1;
	public const MAGENTA = 2;
	public const LIGHT_BLUE = 3;
	public const YELLOW = 4;
	public const LIME = 5;
	public const PINK = 6;
	public const GRAY = 7;
	public const LIGHT_GRAY = 8;
	public const CYAN = 9;
	public const PURPLE = 10;
	public const BLUE = 11;
	public const BROWN = 12;
	public const GREEN = 13;
	public const RED = 14;
	public const BLACK = 15;

	public CONST META_TO_NAMES = [self::WHITE => "White", self::ORANGE => "Orange", self::MAGENTA => "Magenta", self::LIGHT_BLUE => "Light Blue", self::YELLOW => "Yellow", self::LIME => "Lime", self::PINK => "Pink", self::GRAY => "Gray", self::LIGHT_GRAY => "Light Gray", self::CYAN => "Cyan", self::PURPLE => "Purple", self::BLUE => "Blue", self::BROWN => "Brown", self::GREEN => "Green", self::RED => "Red", self::BLACK => "Black"];

	public CONST NAMES_TO_META = ["White" => self::WHITE, "Orange" => self::ORANGE, "Magenta" => self::MAGENTA, "Light Blue" => self::LIGHT_BLUE, "Yellow" => self::YELLOW, "Lime" => self::LIME, "Pink" => self::PINK, "Gray" => self::GRAY, "Light Gray" => self::LIGHT_GRAY, "Cyan" => self::CYAN, "Purple" => self::PURPLE, "Blue" => self::BLUE, "Brown" => self::BROWN, "Green" => self::GREEN, "Red" => self::RED, "Black" => self::BLACK];
}