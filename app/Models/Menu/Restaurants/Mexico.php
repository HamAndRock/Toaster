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
use Nette\Utils\Strings;
use Symfony\Component\DomCrawler\Crawler;


class Mexico extends Restaurant
{
	/** @var string */
	public const API_LINK = 'https://menicka.cz/api/iframe/?id=324';

	/** @var int */
	public const PRICE = 115;


	/**
	 * Get restaurant name
	 * @return string
	 */
	function getName(): string
	{
		return 'Mexická restaurace';
	}


	/**
	 * @return string
	 */
	public function getSlug(): string
	{
		return 'mexicka-restaurace';
	}


	/**
	 * Get link to restaurant
	 * @return string
	 */
	function getLink(): string
	{
		return 'http://www.mexico-jicin.cz/poledni-menu-mexico.aspx';
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
		$crawler->filter('.menu')->each(
			function (Crawler $day, int $i) use (&$menu): void {

				// Weekend
				if ($i > 4) {
					return;
				}

				// Find soups
				$day->filter('.soup')->each(
					function (Crawler $soup) use (&$menu, $i): void {
						$menu[$i]['soups'][] = new Item(
							$this->deleteAlergens(
								$soup->filter('.food')->text()
							)
						);
					}
				);

				// Find meals
				$day->filter('.main')->each(
					function (Crawler $soup) use (&$menu, $i): void {
						$menu[$i]['meals'][] = new Item(
							$this->deleteAlergens(
								$soup->filter('.food')->text()
							),
							(int)Strings::before($soup->filter('.prize')->text(), ' Kč'
							)
						);
					}
				);
			}
		);

		$this->menu = $menu;
	}


	/**
	 * Delete alergens from name
	 * @param string $meal
	 * @return string
	 */
	private function deleteAlergens(string $meal): string
	{
		preg_match('/^\D*(?=\d)/', $meal, $m);
		return isset($m[0]) ? $m[0] : $meal;
	}
}
