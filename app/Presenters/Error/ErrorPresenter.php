<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2018 Jiří Svěcený
 * @version 08.11.2018
 */

declare(strict_types=1);

namespace App\Presenters\Error;

use App\Models\UI\BasePresenter;
use Nette\Application;
use NetteModule;
use Tracy\ILogger;


class ErrorPresenter extends BasePresenter
{
	/**
	 * @var ILogger
	 * @inject
	 */
	public $logger;


	/**
	 * Processes a error request
	 * @param Application\Request $request
	 * @return Application\IResponse
	 */
	public function run(Application\Request $request): Application\IResponse
	{
		$exception = $request->getParameter('exception');

		// Manage bad ajax request
		if ($this->ajax) {
			$this->payload->error = true;
			$this->terminate();
		}

		// Log bad request exception code
		if ($exception instanceof Application\BadRequestException) {
			$this->logger->log('HTTP code ' . $exception->getHttpCode(), ILogger::INFO);
		}

		// Some dark magic to get presenter
		return (new NetteModule\ErrorPresenter($this->logger))->run($request);
	}
}
