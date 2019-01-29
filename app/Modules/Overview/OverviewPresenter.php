<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2018 Jiří Svěcený
 * @version 17.07.2018
 */

declare(strict_types=1);

namespace App\Modules\Overview;

use App\Models\UI\BasePresenter;


/**
 * Abstract parent for Section presenters
 */
abstract class OverviewPresenter extends BasePresenter
{
	public const MESSAGE_SUCCESS = 'alert-success';
	public const MESSAGE_DANGER = 'alert-danger';
	public const MESSAGE_INFO = 'alert-info';
}
