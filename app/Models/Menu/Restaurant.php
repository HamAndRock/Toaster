<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2019 Jiří Svěcený
 * @version 08.01.2019
 */

declare(strict_types=1);

namespace App\Models\Menu;


abstract class Restaurant implements IRestaurant
{
	/** @var array|null */
	protected $menu;


    /**
     * Get soups
     * @param int $dayNumber
     * @return Item[]
     */
    public function getSoups(int $dayNumber): array
	{
		if ($this->menu === null) {
			$this->convert();
		}

		return $this->menu[$dayNumber]['soups'] ?? [];
	}


    /**
     * Get meals
     * @param int $dayNumber
     * @return Item[]
     */
    public function getMeals(int $dayNumber): array
	{
		if ($this->menu === null) {
			$this->convert();
		}

		return $this->menu[$dayNumber]['meals'] ?? [];
	}


	abstract function convert(): void;
}
