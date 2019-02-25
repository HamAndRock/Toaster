<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

// Register debug mode
$debugMode = $_SERVER['SERVER_NAME'] === 'localhost';

$configurator = new Nette\Configurator;
$configurator->setDebugMode($debugMode);
$configurator->enableTracy(__DIR__ . '/../log');
$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');

return $configurator->createContainer();
