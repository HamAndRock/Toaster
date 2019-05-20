<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2019 Jiří Svěcený
 * @version 14.03.2019
 */

declare(strict_types=1);

namespace App\Models\Database\ORM\Menu;

use App\Models\Database\ORM\BaseEntity;
use DateTimeImmutable;


/**
 * @property int $id {primary}
 * @property string $restaurant
 * @property string $name
 * @property DateTimeImmutable $date
 * @property int|null $price {default null}
 * @property int $votes {default 0}
 * @property string $type {enum self::TYPE_*}
 */
class Food extends BaseEntity
{
	/** @var string */
	public const TYPE_SOUP = 'SOUP';

	/** @var string */
	public const TYPE_MEAL = 'MEAL';
}
