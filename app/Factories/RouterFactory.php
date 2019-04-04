<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2018 Jiří Svěcený
 * @version 17.07.2018
 */

declare(strict_types=1);

namespace App\Factories;

use Nette\Application\Routers\RouteList;


class RouterFactory
{
	/**
	 * Return list of routes in application
	 * @return RouteList
	 */
	public static function create(): RouteList
	{
		$router = new RouteList;

		// Register home presenter
		$router->addRoute('[<day>]', 'Overview:Home:Default');

		return $router;
	}
}
