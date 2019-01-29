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


class Kozlovna extends Restaurant
{
	/** @var string */
	public const API_LINK = 'https://kozlovnajicin.cz/denni-menu';

	/** @var int */
	public const PRICE = 115;


	/**
	 * Get restaurant name
	 * @return string
	 */
	function getName(): string
	{
		return 'Kozlovna U Anděla';
	}


	/**
	 * @return string
	 */
	public function getSlug(): string
	{
		return 'kozlovna-u-andela';
	}


	/**
	 * Get link to restaurant
	 * @return string
	 */
	function getLink(): string
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
		$contnt = $crawler->filter('.denni-menu .single-page-content');

		// Find meals
		$contnt->filter('ol')->each(
			function (Crawler $item, int $i) use (&$menu): void {
				$namesOfMeals = $item->filter("li");
				$namesOfMeals->each(
					function (Crawler $item) use (&$menu, $i): void {
						$menu[$i]['meals'][] = $ok = new Item(
							$this->deleteAlergens(
								strip_tags($item->text())
							), self::PRICE
						);
					}
				);
			}
		);

		$dayNumber = 0;

		// Find soups
		$contnt->filter('p')->each(
			function (Crawler $item, int $i) use (&$menu, &$dayNumber): void {

				// Drop header and empty paragraphs
				if ($i === 0 || $i > 10) {
					return;
				}

				if ($i % 2 === 0) {
					$menu[$dayNumber]['soups'][] = new Item(
						Strings::before($item->text(), ' (', 1)
					);
				} else {
					return; // Name of the day
				}

				$dayNumber++;
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
