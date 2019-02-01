<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2019 Jiří Svěcený
 * @version 08.01.2019
 */

declare(strict_types=1);

namespace App\Models\Menu;


use App\Models\Cache\CacheHandler;
use Nette\SmartObject;


/**
 * @property-read string $name
 * @property-read string $slug
 * @property-read string $link
 */
abstract class Restaurant implements IRestaurant
{
	use SmartObject;

	/** @var array|null */
	protected $menu;

	/** @var CacheHandler */
	private $cache;


	/**
	 * Restaurant constructor
	 * @param CacheHandler $cache
	 */
	public function __construct(CacheHandler $cache)
	{
		$this->cache = $cache;
	}


	/**
	 * Get soups
	 * @param int $dayNumber
	 * @return Item[]
	 */
	public function getSoups(int $dayNumber): array
	{
		return $this->menu[$dayNumber]['soups'] ?? [];
	}


	/**
	 * Get meals
	 * @param int $dayNumber
	 * @return Item[]
	 */
	public function getMeals(int $dayNumber): array
	{
		return $this->menu[$dayNumber]['meals'] ?? [];
	}


	/**
	 * Load from cache or build restaurant menu
	 */
	public function cache(): void
	{
		$callback = function (): array {
			return $this->build();
		};

		$this->menu = $this->cache->load($this->slug . date('W'), $callback, []);
	}


	/**
	 * @throws BadRestaurantResponseException
	 */
	abstract public function build(): array;
}
