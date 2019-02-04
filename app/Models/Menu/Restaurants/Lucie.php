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


class Lucie extends Restaurant
{
	/** @var string */
	public const API_LINK = 'http://www.penzion-lucie.cz/#denni-menu';


	/**
	 * Get restaurant name
	 * @return string
	 */
	public function getName(): string
	{
		return 'Penzion Lucie';
	}


	/**
	 * @return string
	 */
	public function getSlug(): string
	{
		return 'lucie';
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
	public function build(): array
	{
		$html = file_get_contents(self::API_LINK);
		$crawler = new Crawler($html);

		$menu = [];

		// Find by day
		$crawler->filter('#denni-menu')->filter('ul')->each(
			function (Crawler $day, int $i) use (&$menu): void {

				// Find foods
				$day->filter('li')->each(
					function (Crawler $meal, int $r) use (&$menu, &$i): void {

						if ($r === 0) {
							$menu[$i]['soups'][] = new Item(
								$meal->filter('.el-content > p')->text(),
								(int) $meal->filter('.uk-child-width-auto')->filter('.el-meta')->text()
							);
						} else {
							$name = Strings::trim($meal->filter('.el-content')->text());
							$price = (int) $meal->filter('.uk-child-width-auto')->filter('.el-meta')->text();

							if ($price > 0) {
								$menu[$i]['meals'][] = new Item($name, $price);
							}
						}
					}
				);
			}
		);

		return $this->menu = $menu;
	}
}
