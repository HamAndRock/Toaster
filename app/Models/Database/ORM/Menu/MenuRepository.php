<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2019 Jiří Svěcený
 * @version 14.03.2019
 */

declare(strict_types=1);

namespace App\Models\Database\ORM\Menu;

use App\Models\Database\ORM\BaseRepository;
use DateTimeImmutable;
use Nextras\Orm\Collection\ICollection;


class MenuRepository extends BaseRepository
{
	/**
	 * Returns possible entity class names for current repository
	 * @return string[]
	 */
	public static function getEntityClassNames(): array
	{
		return [Food::class];
	}


	/**
	 * Find food by restaurant and date
	 * @param string $restaurantSlug
	 * @param DateTimeImmutable $date
	 * @return ICollection|Food[]
	 */
	public function findByRestaurantAndDate(string $restaurantSlug, DateTimeImmutable $date): ICollection
	{
		return $this->findBy(['restaurant' => $restaurantSlug, 'date' => $date]);
	}
}
