<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2019 Jiří Svěcený
 * @version 25.02.2019
 */

declare(strict_types=1);

namespace App\Modules\Overview\Presenters\Feed;

use App\Models\Menu\RestaurantsFactory;
use App\Modules\Overview\OverviewPresenter;
use Nette;


class FeedPresenter extends OverviewPresenter
{
	/**
	 * @var RestaurantsFactory
	 * @inject
	 */
	public $restaurantsFactory;


	/**
	 * Send API feed
	 * @throws Nette\Application\AbortException
	 */
	public function actionFeed(): void
	{
		$this->sendJson(
			$this->build()
		);
	}


	/**
	 * Build JSON response
	 * @return array
	 */
	private function build(): array
	{
		$restaurants = $this->restaurantsFactory->getRestaurants();
		$weekMenu = [];

		foreach ($restaurants as $restaurant) {
			$menu = [];

			// Every week day
			for ($day = 0; $day <= 5; $day++) {
				$meals = [];
				$soups = [];

				// Each meals by day and restaurant
				foreach ($restaurant->getMeals($day) as $meal) {
					$meals[] = [
						'name' => $meal->getName(),
						'price' => $meal->getPrice(),
					];
				}

				// Each soups by day and restaurant
				foreach ($restaurant->getSoups($day) as $soup) {
					$soups[] = [
						'name' => $soup->getName(),
						'price' => $soup->getPrice(),
					];
				}

				$menu[$day] = [
					'soups' => $soups,
					'meals' => $meals,
				];
			}

			$weekMenu[] = [
				'slug' => $restaurant->getSlug(),
				'name' => $restaurant->getName(),
				'link' => $restaurant->getLink(),
				'menu' => $menu,
			];
		}

		return $weekMenu;
	}
}
