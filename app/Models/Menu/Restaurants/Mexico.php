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
use Nette\Utils\Strings;
use Symfony\Component\DomCrawler\Crawler;


final class Mexico extends Restaurant
{
	/** @var string */
	public const API_LINK = 'https://menicka.cz/api/iframe/?id=324';

	/** @var int */
	public const PRICE = 115;


	/**
	 * Get restaurant name
	 * @return string
	 */
	public function getName(): string
	{
		return 'Mexická restaurace';
	}


	/**
	 * @return string
	 */
	public function getSlug(): string
	{
		return 'MEXICO';
	}


	/**
	 * Get link to restaurant
	 * @return string
	 */
	public function getLink(): string
	{
		return 'http://www.mexico-jicin.cz/poledni-menu-mexico.aspx';
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
		$crawler->filter('.menu')->each(
			function (Crawler $day, int $i) use (&$date, &$repository): void {

				// Weekend
				if ($i > 4) {
					return;
				}

				// Find soups
				$day->filter('.soup')->each(
					function (Crawler $soup) use ($date, &$repository): void {
						$food = new Food;
						$food->date = DateTimeImmutable::createFromMutable($date);;
						$food->type = Food::TYPE_SOUP;
						$food->restaurant = $this->slug;
						$food->name = self::alergens($soup->filter('.food')->text());

						$repository->persist($food);
					}
				);

				// Find meals
				$day->filter('.main')->each(
					function (Crawler $soup) use ($date, &$repository): void {
						$food = new Food;
						$food->date = DateTimeImmutable::createFromMutable($date);;
						$food->type = Food::TYPE_MEAL;
						$food->restaurant = $this->slug;
						$food->name = self::alergens($soup->filter('.food')->text());
						$food->price = (int) Strings::before($soup->filter('.prize')->text(), ' Kč');

						$repository->persist($food);
					}
				);

				$date->modify('+1 day');
			}
		);

		$repository->flush();
	}


	/**
	 * Delete alergens from name
	 * @param string $meal
	 * @return string
	 */
	private static function alergens(string $meal): string
	{
		preg_match('/^\D*(?=\d)/', $meal, $m);
		return $m[0] ?? $meal;
	}
}
