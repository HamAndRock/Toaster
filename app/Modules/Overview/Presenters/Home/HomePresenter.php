<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2018 Jiří Svěcený
 * @version 17.07.2018
 */

declare(strict_types=1);

namespace App\Modules\Overview\Presenters\Home;

use App\Models\Menu\RestaurantsFactory;
use App\Modules\Overview\OverviewPresenter;


class HomePresenter extends OverviewPresenter
{
	/** @var array */
	public const DAYS = [
		'pondeli' => 'pondělí',
		'utery' => 'úterý',
		'streda' => 'středa',
		'ctvrtek' => 'čtvrtek',
		'patek' => 'pátek',
	];

	/**
	 * @var RestaurantsFactory
	 * @inject
	 */
	public $restaurantsFactory;


	/**
	 * Render menu
	 * @param string|null $slugName
	 */
	public function renderDefault(string $slugName = null): void
	{
		$slugs = array_keys(self::DAYS);

		// Redirect to actual day if slug is incorrect
		if (!in_array($slugName, $slugs, true) || $slugName === null ) {
			$actual = (int) date('N'); // Actual week day
			$temp = $actual > 5 ? 1 : $actual; // Ignore weekends
			$this->redirect('this', $slugs[$temp - 1]);
		}

		$this->template->restaurants = $this->restaurantsFactory->getRestaurants();
		$this->template->now = in_array($slugName, $slugs, true) ? array_search($slugName, $slugs, true) : 0;
		$this->template->days = self::DAYS;
	}
}
