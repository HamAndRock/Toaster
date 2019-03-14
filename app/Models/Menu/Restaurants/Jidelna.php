<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2018 Jiří Svěcený
 * @version 17.07.2018
 */

declare(strict_types=1);

namespace App\Models\Menu\Restaurants;

use App\Models\Database\ORM\Menu\Food;
use App\Models\Menu\Restaurant;
use DateTime;
use Nette\Utils\Strings;
use Symfony\Component\DomCrawler\Crawler;


final class Jidelna extends Restaurant
{
	/** @var string */
	public const API_LINK = 'https://www.vos-sps-jicin.cz/?main=jidelna_jidel';

	/** @var int */
	public const PRICE = 33;


	/**
	 * Get restaurant name
	 * @return string
	 */
	public function getName(): string
	{
		return 'Školní jídelna VOŠ a SPŠ Jičín';
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
	 * Get restaurant slug
	 * @return string
	 */
	public function getSlug(): string
	{
		return 'JIDELNA';
	}


	/**
	 * Convert raw data
	 */
	public function build(): void
	{
		$html = file_get_contents(self::API_LINK);
		$repository = $this->menuRepository;
		$crawler = new Crawler($html);

		$date = new DateTime('monday this week');

		// Find meals
		$crawler->filter('#container #content .odsazeni')->filter('table')->each(
			function (Crawler $item) use (&$date, &$repository): void {
				$item->filter('tr')->each(
					function (Crawler $item, int $r) use (&$date, &$repository): void {
						if ($r == 0) {
							return;
						}

						// Soup
						if ($r == 1) {
							$soup = $item->filter('.jidelnicek-typ-v')->text();
							preg_match("/(Pol(é|e)vka)( -|)(?<name>\W.*)/", $soup, $matches);

							$food = new Food;
							$food->date = $date;
							$food->name = Strings::firstUpper(Strings::trim($matches['name']));
							$food->restaurant = $this->slug;
							$food->type = Food::TYPE_SOUP;

							$repository->persist($food);
						}

						// Meal
						if ($r == 2 || $r == 3) {
							$data = $item->filter('.jidelnicek-typ-v');

							if ($data->count() > 0) {
								$food = new Food;
								$food->date = $date;
								$food->name = $item->filter('.jidelnicek-typ-v')->text();
								$food->restaurant = $this->slug;
								$food->type = Food::TYPE_MEAL;
								$food->price = self::PRICE;

								$repository->persist($food);
							}
						}
					}
				);

				$date->modify('+1 day');
			}
		);

		$repository->flush();
	}
}