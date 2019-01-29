<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2018 Jiří Svěcený
 * @version 17.07.2018
 */

declare(strict_types=1);

namespace App\Models\Menu;

use Nette\DI\Container;


class RestaurantsFactory
{
	/**
	 * @var Container
	 */
	private $container;


	/**
	 * RestaurantsFactory constructor.
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}


	/**
	 * Get restaurants
	 * @return IRestaurant[]
	 */
	public function getRestaurants(): array
	{
		$restaurants = [];

		foreach ($this->container->findByTag('restaurant') as $name => $value) {
			$restaurants[] = $this->container->getService($name);
		}

		return $restaurants;
	}
}