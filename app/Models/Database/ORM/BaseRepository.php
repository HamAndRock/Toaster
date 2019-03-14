<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2018 Jiří Svěcený
 * @version 03.12.2018
 */

declare(strict_types=1);

namespace App\Models\Database\ORM;

use App\Models\Database\DatabaseModel;
use Nextras\Orm\Repository\Repository;


/**
 * @property BaseMapper $mapper
 * @property DatabaseModel $model
 */
abstract class BaseRepository extends Repository
{
}
