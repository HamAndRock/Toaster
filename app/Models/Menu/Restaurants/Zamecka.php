<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2019 Jiří Svěcený
 * @version 07.01.2019
 */

declare(strict_types=1);

namespace App\Models\Menu\Restaurants;

use App\Models\Menu\Item;
use App\Models\Menu\Restaurant;
use Symfony\Component\DomCrawler\Crawler;


class Zamecka extends Restaurant
{
	/** @var string */
	public const API_LINK = 'http://zameckajicin.cz/frontpage/menu-na-tyden';


	/**
	 * Get restaurant name
	 * @return string
	 */
	public function getName(): string
	{
		return 'Zámecká restaurace';
	}


	/**
	 * @return string
	 */
	public function getSlug(): string
	{
		return 'zamecka-restaurace';
	}


	/**
	 * Get link to restaurant
	 * @return string
	 */
	public function getLink(): string
	{
		return self::API_LINK;
	}


	/**
	 * Convert data from raw source
	 */
	public function convert(): void
	{
		$html = file_get_contents(self::API_LINK);
		$crawler = new Crawler($html);

		$menu = [];

		// Find by day
		$crawler->filter('.menu-day')->each(
			function (Crawler $day, int $i) use (&$menu): void {
				$day->filter('.menu-list__item')->each(
					function (Crawler $item, int $r) use (&$menu, $i): void {

						// Name of day
						if ($r === 0) {
							return;
						}

						// Find soup
						if ($r === 1) {
							$menu[$i]['soups'][] = new Item(
								$item->filter('h4')->text()
							);

							return;
						}

						$menu[$i]['meals'][] = new Item(
							$item->filter('h4 span')->text(),
							(int) $item->filter('.menu-list__item-price')->text()
						);
					}
				);
			}
		);

		$this->menu = $menu;
	}
}
