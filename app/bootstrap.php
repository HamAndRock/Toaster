<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;
$configurator->enableTracy(__dir__ . '/../log');
$configurator->setTempDirectory(__dir__ . '/../temp');

$configurator->addConfig(__dir__ . '/config/config.neon');
$configurator->addConfig(__dir__ . '/config/config.local.neon');

return $configurator->createContainer();
