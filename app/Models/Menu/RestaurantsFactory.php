<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2018 Jiří Svěcený
 * @version 17.07.2018
 */

declare(strict_types=1);

namespace App\Models\Menu;

use Nette\DI\Container;
use Tracy\ILogger;


class RestaurantsFactory
{
	/** @var Container */
	private $container;

	/** @var ILogger */
	private $logger;


	/**
	 * RestaurantsFactory constructor.
	 * @param Container $container
	 * @param ILogger $logger
	 */
	public function __construct(Container $container, ILogger $logger)
	{
		$this->container = $container;
		$this->logger = $logger;
	}


	/**
	 * Get restaurants
	 * @return IRestaurant[]
	 */
	public function getRestaurants(): array
	{
		$restaurants = [];

		foreach ($this->container->findByTag('restaurant') as $name => $value) {
			/** @var Restaurant $restaurant */
			$restaurant = $this->container->getService($name);

			try {
				$restaurant->cache();
			} catch (BadRestaurantResponseException $exception) {
				$this->logger->log(
					sprintf('Restaurant "%s" did not return a valid response.', $restaurant->name)
				);
				continue;
			}

			$restaurants[] = $restaurant;
		}

		return $restaurants;
	}
}