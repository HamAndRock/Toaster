<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2019 Jiří Svěcený
 * @version 14.03.2019
 */

declare(strict_types=1);

namespace App\Models\Console;

use App\Models\Menu\Restaurant;
use App\Models\Menu\RestaurantsFactory;
use Nette\Utils\Strings;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use Tracy\ILogger;


class BuildCommand extends Command
{
	/** @var string */
	protected static $defaultName = 'restaurants:build';

	/** @var RestaurantsFactory */
	private $restaurantsFactory;

	/** @var ILogger */
	private $logger;


	/**
	 * BuildCommand constructor
	 * @param RestaurantsFactory $restaurantsFactory
	 * @param ILogger $logger
	 * @param string|null $name
	 */
	public function __construct(RestaurantsFactory $restaurantsFactory, ILogger $logger, string $name = null)
	{
		parent::__construct($name);
		$this->restaurantsFactory = $restaurantsFactory;
		$this->logger = $logger;

		// May you want to build only one restaurant
		$this->addArgument('restaurant', InputArgument::OPTIONAL, 'Build specifically restaurant.');
	}


	/**
	 * Execute command
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		/** @var string $specifically */
		$specifically = $input->getArgument('restaurant');
		$restaurants = $this->restaurantsFactory->getRestaurants();
		
		/** @var Restaurant $restaurant */
		foreach ($restaurants as $restaurant) {
			if ($specifically !== null && Strings::upper($specifically) !== $restaurant->slug) {
				continue;
			}

			try {
				$restaurant->build();
			} catch (Throwable $exception) {
				$this->logger->log($exception, ILogger::EXCEPTION);
			}
		}
	}
}
