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
		$days = ['pondeli', 'utery', 'streda', 'ctvrtek', 'patek'];

		$this->template->restaurants = $this->restaurantsFactory->getRestaurants();
		$this->template->day = in_array($slugName, $days) ? array_search($slugName, $days) : 0;
	}
}
