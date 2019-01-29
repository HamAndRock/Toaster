<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2019 Jiří Svěcený
 * @version 07.01.2019
 */

declare(strict_types=1);

namespace App\Models\Menu;


interface IRestaurant
{
	/**
	 * Get restaurant name
	 * @return string
	 */
	function getName(): string;

	/**
	 * Get link to restaurant
	 * @return string
	 */
	function getLink(): string;

	/**
	 * Get restaurant slug
	 * @return string
	 */
	function getSlug(): string;

	/**
	 * Get soups
	 * @param int $dayNumber
	 * @return Item[]
	 */
	function getSoups(int $dayNumber): array;

	/**
	 * Get meals
	 * @param int $dayNumber
	 * @return Item[]
	 */
	function getMeals(int $dayNumber): array;
}
