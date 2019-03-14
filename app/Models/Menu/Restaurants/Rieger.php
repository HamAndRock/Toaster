<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2018 Jiří Svěcený
 * @version 12.02.2019
 */

declare(strict_types=1);

namespace App\Models\Menu\Restaurants;

use App\Models\Database\ORM\Menu\Food;
use App\Models\Menu\Restaurant;
use DateTime;
use DateTimeImmutable;
use Nette\Utils\Strings;
use Symfony\Component\DomCrawler\Crawler;


final class Rieger extends Restaurant
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
		return 'RIEGER';
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
		$crawler->filter('.menu-widget')->each(
			function (Crawler $day) use (&$date, &$repository): void {
				$day->filter('.food-item')->each(
					function (Crawler $meal, int $r) use (&$repository, $date): void {
						$title = Strings::match(
							$meal->filter('h6 > a')->text(), '/([)]|[\.])(?<name>\D.*)/'
						);

						// Invalid food name
						if (isset($title['name']) === false) {
							return;
						}

						$food = new Food;
						$food->name = Strings::trim($title['name']);
						$food->restaurant = $this->slug;
						$food->date = DateTimeImmutable::createFromMutable($date);

						// Fist item is soup
						if ($r === 0) {
							$food->type = Food::TYPE_SOUP;
						} else {
							$food->type = Food::TYPE_MEAL;
							$food->price = (int) $meal->filter('.price > span')->text();
						}

						$repository->persist($food);
					}
				);

				$date->modify('+1 day');
			}
		);

		$repository->flush();
	}
}