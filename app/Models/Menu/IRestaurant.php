<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2019 Jiří Svěcený
 * @version 07.01.2019
 */

declare(strict_types=1);

namespace App\Models\Menu;

use App\Models\Database\ORM\Menu\Food;
use DateTimeImmutable;
use Nextras\Orm\Collection\ICollection;


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
	 * @param DateTimeImmutable $date
	 * @return ICollection|Food[]
	 */
	function getSoups(DateTimeImmutable $date): ICollection;


	/**
	 * Get meals
	 * @param DateTimeImmutable $date
	 * @return ICollection|Food[]
	 */
	function getMeals(DateTimeImmutable $date): ICollection;
}
