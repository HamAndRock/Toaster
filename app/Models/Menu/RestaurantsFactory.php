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
	 * @return Restaurant[]
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