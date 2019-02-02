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
		if ($slugName === null || !in_array($slugName, $slugs)) {
			$actual = (int) date('N'); // Actual week day
			$this->redirect('this', $slugs[$actual > 5 ? 4 : $actual - 1]);
		}

		$this->template->restaurants = $this->restaurantsFactory->getRestaurants();
		$this->template->now = array_search($slugName, $slugs, true);
		$this->template->days = self::DAYS;
	}
}
