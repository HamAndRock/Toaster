<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2018 Jiří Svěcený
 * @version 12.02.2019
 */

declare(strict_types=1);

namespace App\Models\Menu\Restaurants;

use App\Models\Menu\Item;
use App\Models\Menu\Restaurant;
use Nette\Utils\Strings;
use Symfony\Component\DomCrawler\Crawler;


class Rieger extends Restaurant
{
	/** @var string */
	public const API_LINK = 'https://objednavkarozvozu.cz/restaurace/238-rieger';


	/**
	 * Get restaurant name
	 * @return string
	 */
	public function getName(): string
	{
		return 'Pizzerie Rieger';
	}


	/**
	 * Get link to restaurant
	 * @return string
	 */
	public function getLink(): string
	{
		return 'https://www.pizzeriejicin.cz';
	}


	/**
	 * Get restaurant slug
	 * @return string
	 */
	public function getSlug(): string
	{
		return 'pizzerie-rieger';
	}


	/**
	 * Convert raw data
	 */
	public function build(): array
	{
		$html = file_get_contents(self::API_LINK);
		$crawler = new Crawler($html);

		$menu = [];

		// Find meals
		$crawler->filter('.menu-widget')->each(
			function (Crawler $day, int $i) use (&$menu): void {
				$day->filter('.food-item')->each(
					function (Crawler $food, int $r) use (&$menu, &$i): void {
						$title = Strings::match(
							$food->filter('h6 > a')->text(), '/([)]|[\.])(?<name>\D.*)/'
						);

						// Invalid food name
						if (isset($title['name']) === false) {
							return;
						}

						// Fist item is soup
						if ($r === 0) {
							$menu[$i]['soups'][] = new Item(
								Strings::trim($title['name'])
							);
						} else {
							$menu[$i]['meals'][] = new Item(
								Strings::trim($title['name']),
								(int) $food->filter('.price > span')->text()
							);
						}
					}
				);
			}
		);

		return $this->menu = $menu;
	}
}