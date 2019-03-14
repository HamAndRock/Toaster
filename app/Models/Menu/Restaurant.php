<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2019 Jiří Svěcený
 * @version 08.01.2019
 */

declare(strict_types=1);

namespace App\Models\Menu;


use App\Models\Database\ORM\Menu\Food;
use App\Models\Database\ORM\Menu\MenuRepository;
use DateTimeImmutable;
use Nette\SmartObject;
use Nextras\Orm\Collection\ICollection;


/**
 * @property-read string $name
 * @property-read string $slug
 * @property-read string $link
 */
abstract class Restaurant implements IRestaurant
{
	use SmartObject;

	/** @var MenuRepository */
	protected $menuRepository;


	/**
	 * Restaurant constructor
	 * @param MenuRepository $menuRepository
	 */
	public function __construct(MenuRepository $menuRepository)
	{
		$this->menuRepository = $menuRepository;
	}


	/**
	 * @param DateTimeImmutable $date
	 * @return ICollection|Food[]
	 */
	public function getSoups(DateTimeImmutable $date): ICollection
	{
		return $this->menuRepository
			->findByRestaurantAndDate($this->slug, $date)->findBy(['type' => Food::TYPE_SOUP]);
	}


	/**
	 * @param DateTimeImmutable $date
	 * @return ICollection|Food[]
	 */
	public function getMeals(DateTimeImmutable $date): ICollection
	{
		return $this->menuRepository
			->findByRestaurantAndDate($this->slug, $date)->findBy(['type' => Food::TYPE_MEAL]);
	}


	/**
	 * Build and save restaurant menu
	 */
	abstract public function build(): void;
}
