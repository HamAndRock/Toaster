<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2018 Jiří Svěcený
 * @version 23.10.2018
 */

declare(strict_types=1);

namespace App\Models\UI;

use LogicException;
use Nette;


/**
 * @property-read bool $ajax
 * @property-read Nette\Bridges\ApplicationLatte\Template|\stdClass $template
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	/**
	 * Return presenter dirname
	 * @return string
	 */
	private function getPresenterDirname(): string
	{
		$fileName = self::getReflection()->getFileName();

		if ($fileName === false) {
			throw new LogicException('Presenter filename must be validate.');
		}

		return dirname($fileName);
	}


	/**
	 * Presenter template file name generator
	 * @return string[]
	 */
	public function formatTemplateFiles(): array
	{
		return [
			$this->getPresenterDirname() . '/templates/' . $this->view . '.latte',
		];
	}


	/**
	 * Presenter layout template file
	 * @return string[]
	 */
	public function formatLayoutTemplateFiles(): array
	{
		// A little hack to get a module folder
		$modulDirname = dirname(dirname($this->getPresenterDirname()));

		return [
			$modulDirname . '/@layout.latte',
		];
	}
}
