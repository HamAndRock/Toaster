<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2019 Jiří Svěcený
 * @version 05.02.2019
 */

declare(strict_types=1);

namespace App\Models\Menu\Restaurants;

use App\Models\Menu\Item;
use App\Models\Menu\Restaurant;
use Nette\Utils\Strings;
use Symfony\Component\DomCrawler\Crawler;


class DelovaKoule extends Restaurant
{
	/** @var string */
	public const API_LINK = 'https://menicka.cz/api/iframe/?id=340';


	/**
	 * Get restaurant name
	 * @return string
	 */
	public function getName(): string
	{
		return 'U Dělové koule';
	}


	/**
	 * @return string
	 */
	public function getSlug(): string
	{
		return 'u-delove-koule';
	}


	/**
	 * Get link to restaurant
	 * @return string
	 */
	public function getLink(): string
	{
		return 'https://www.hoteljicin.cz/cs/restaurace/';
	}


	/**
	 * Convert data from raw source
	 */
	public function build(): array
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
							(int) Strings::before($soup->filter('.prize')->text(), ' Kč')
						);
					}
				);
			}
		);

		return $this->menu = $menu;
	}


	/**
	 * Delete alergens from name
	 * @param string $meal
	 * @return string
	 */
	private function deleteAlergens(string $meal): string
	{
		preg_match('/^\D*(?=\d)/', $meal, $m);
		return $m[0] ?? $meal;
	}
}
