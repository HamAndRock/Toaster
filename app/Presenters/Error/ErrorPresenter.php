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
use Nette\Http\Request;
use NetteModule;
use Tracy\ILogger;
use Tracy\Logger;


class ErrorPresenter extends BasePresenter
{
	/**
	 * @var ILogger
	 * @inject
	 */
	public $logger;

	/**
	 * @var Request
	 * @inject
	 */
	public $request;


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
			return new Application\Responses\JsonResponse(['error' => true]);
		}

		// Handle bad request
		if ($exception instanceof Application\BadRequestException) {
			$this->logger->log(
				sprintf('HTTP code %s (%s)', $exception->getHttpCode(), $this->request->getUrl()->absoluteUrl), Logger::INFO
			);
		}

		// Some dark magic to get presenter
		return (new NetteModule\ErrorPresenter($this->logger))->run($request);
	}
}
