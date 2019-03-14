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
use InvalidArgumentException;
use Nette\Utils\Strings;
use Symfony\Component\DomCrawler\Crawler;


final class Lucie extends Restaurant
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
		return 'LUCIE';
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
		$crawler->filter('#denni-menu')->filter('ul')->each(
			function (Crawler $day, int $i) use (&$date, &$repository): void {
				try {
					// Find foods
					$day->filter('li')->each(
						function (Crawler $meal, int $r) use (&$date, &$repository): void {
							$food = new Food;
							$food->date = DateTimeImmutable::createFromMutable($date);;
							$food->restaurant = $this->slug;
							$food->name = Strings::trim($meal->filter('.el-content')->text());

							if ($r === 0) {
								$food->type = Food::TYPE_SOUP;
							} else {
								$food->type = Food::TYPE_MEAL;
								$food->price = (int) $meal->filter('.uk-child-width-auto')->filter('.el-meta')->text();
							}

							$repository->persist($food);
						}
					);
				} catch (InvalidArgumentException $exception) {
					return;
				}

				$date->modify('+1 day');
			}
		);

		$repository->flush();
	}
}
