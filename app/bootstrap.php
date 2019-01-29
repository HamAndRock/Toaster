<?php

declare(strict_types=1);

require __dir__ . '/../vendor/autoload.php';

$configurator = new nette\configurator;
$configurator->enabletracy(__dir__ . '/../log');
$configurator->settempdirectory(__dir__ . '/../temp');

$configurator->addconfig(__dir__ . '/config/config.neon');
$configurator->addconfig(__dir__ . '/config/config.local.neon');

return $configurator->createcontainer();
