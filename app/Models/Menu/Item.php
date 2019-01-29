<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2019 Jiří Svěcený
 * @version 07.01.2019
 */

declare(strict_types=1);

namespace App\Models\Menu;

use Nette\SmartObject;


/**
 * @property-read string $name
 * @property-read int|null $price
 */
class Item
{
	use SmartObject;

	/** @var string */
	private $name;

	/** @var int|null */
	private $price;


	/**
	 * Item constructor
	 * @param string $name
	 * @param int $price |null
	 */
	public function __construct(string $name, int $price = null)
	{
		$this->name = $name;
		$this->price = $price;
	}


	/**
	 * Get item name
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}


	/**
	 * Get item price
	 * @return int|null
	 */
	public function getPrice(): ?int
	{
		return $this->price;
	}
}
