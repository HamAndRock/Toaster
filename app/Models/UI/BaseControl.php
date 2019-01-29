<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2018 Jiří Svěcený
 * @version 23.10.2018
 */

declare(strict_types=1);

namespace App\Models\UI;

use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\Template;


/**
 * @property-read BasePresenter $presenter
 * @property-read Template|\stdClass $template
 */
abstract class BaseControl extends Control
{
}
