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


final class Kozlovna extends Restaurant
{
	/** @var string */
	public const API_LINK = 'https://kozlovnajicin.cz/denni-menu';

	/** @var int */
	public const PRICE = 115;


	/**
	 * Get restaurant name
	 * @return string
	 */
	public function getName(): string
	{
		return 'Kozlovna U Anděla';
	}


	/**
	 * @return string
	 */
	public function getSlug(): string
	{
		return 'KOZLOVNA';
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

		// Find meals
		$crawler->filter('.denni-menu .single-page-content')->filter('ol')->each(
			function (Crawler $item, int $i) use (&$date, &$repository): void {
				$meals = $item->filter('li');
				$meals->each(
					function (Crawler $item) use (&$repository, $date): void {
						$food = new Food;
						$food->date = DateTimeImmutable::createFromMutable($date);;
						$food->price = self::PRICE;
						$food->type = Food::TYPE_MEAL;
						$food->restaurant = $this->slug;
						$food->name = self::alergens($item->text());

						$repository->persist($food);
					}
				);

				$date->modify('+1 day');
			}
		);

		$date = new DateTime('monday this week');

		// Find soups
		$crawler->filter('.denni-menu .single-page-content')->filter('p')->each(
			function (Crawler $item, int $i) use (&$date, &$repository): void {
				// Skip header and empty paragraphs
				if ($i === 0 || $i > 10) {
					return;
				}

				if ($i % 2 === 0) {
					$food = new Food;
					$food->date = DateTimeImmutable::createFromMutable($date);;
					$food->type = Food::TYPE_SOUP;
					$food->restaurant = $this->slug;
					$food->name = (string) Strings::before($item->text(), ' (', 1);

					$repository->persist($food);

					$date->modify('+1 day');
				} else {
					return; // Name
				}
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
