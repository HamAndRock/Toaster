<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2019 Jiří Svěcený
 * @version 07.01.2019
 */

declare(strict_types=1);

namespace App\Models\Menu\Restaurants;

use App\Models\Database\ORM\Menu\Food;
use App\Models\Menu\Restaurant;
use DateTime;
use DateTimeImmutable;
use Symfony\Component\DomCrawler\Crawler;


final class Zamecka extends Restaurant
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
		return 'ZAMECKA';
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
	public function build(): void
	{
		$html = file_get_contents(self::API_LINK);
		$repository = $this->menuRepository;
		$crawler = new Crawler($html);

		$date = new DateTime('monday this week');

		// Find by day
		$crawler->filter('.menu-day')->each(
			function (Crawler $day, int $i) use (&$date, &$repository): void {
				// Ignore first each
				if ($i > 0) {
					$date->modify('+1 day');
				}

				$day->filter('.menu-list__item')->each(
					function (Crawler $item, int $r) use (&$date, &$repository): void {
						$food = new Food;
						$food->date = DateTimeImmutable::createFromMutable($date);;
						$food->restaurant = $this->slug;

						switch ($r) {
							case 0:
								return;
							case 1:
								$food->type = Food::TYPE_SOUP;
								$food->name = $item->filter('h4')->text();
								break;
							default:
								$food->type = Food::TYPE_MEAL;
								$food->name = $item->filter('h4')->text();
								$food->price = (int) $item->filter('.menu-list__item-price')->text();
						}

						$repository->persist($food);
					}
				);
			}
		);

		$repository->flush();
	}
}
