<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2018 Jiří Svěcený
 * @version 17.07.2018
 */

declare(strict_types=1);

namespace App\Modules\Overview\Presenters\Home;

use App\Models\Menu\IRestaurant;
use App\Models\Menu\RestaurantsFactory;
use App\Modules\Overview\OverviewPresenter;
use DateTime;
use DateTimeImmutable;
use Nette\Application\Responses\JsonResponse;
use Nextras\Dbal\Connection;


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

	/** @var IRestaurant[] */
	private $restaurants;

	/** @var Connection */
	private $connection;


	/**
	 * HomePresenter constructor.
	 * @param RestaurantsFactory $restaurantsFactory
	 * @param Connection $connection
	 */
	public function __construct(RestaurantsFactory $restaurantsFactory, Connection $connection)
	{
		parent::__construct();

		$this->restaurants = $restaurantsFactory->getRestaurants();
		$this->connection = $connection;
	}


	/**
	 * Render menu
	 * @param string|null $day
	 */
	public function renderDefault(string $day = null): void
	{
		$slugs = array_keys(self::DAYS);
		$date = new DateTimeImmutable('monday this week');

		// Day name is incorrect
		if ($day === null || !in_array($day, $slugs, true)) {
			$date = new DateTime;

			// Weekend
			if ($date->format('N') > 5) {
				$this->redirect('this', 'patek');
			} else {
				$this->redirect('this', $slugs[(int) $date->format('N') - 1]);
			}
		}

		$this->template->days = self::DAYS;
		$this->template->restaurants = $this->restaurants;

		// Calculate date shift
		$this->template->date = $date->modify(
			sprintf('+%s days', (string) array_search($day, $slugs, true))
		);
	}


	public function handleVote(string $foodId): void
	{
		$this->connection->query('UPDATE menu SET votes = votes + 1 WHERE id = %i', $foodId);
		$this->sendResponse(new JsonResponse(['state' => 'ok']));
	}
}
