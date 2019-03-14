<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2019 Jiří Svěcený
 * @version 14.03.2019
 */

declare(strict_types=1);

namespace App\Models\Database;

use App\Models\Database\ORM\Menu\MenuRepository;
use Nextras\Orm\Model\Model;


/**
 * @property-read MenuRepository $menu
 */
class DatabaseModel extends Model
{
}
