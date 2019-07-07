<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2019 Jiří Svěcený
 * @version 07.07.2019
 */

declare(strict_types=1);

namespace App\Models\AWS;

use Maxbanton\Cwh\Handler\CloudWatch;


class CloudWatchLoggerHandler
{
	/**
	 * Create AWS Cloud Watch monolog handler
	 * @param Connector $connector
	 * @param string $groupName
	 * @param string $instanceName
	 * @return CloudWatch
	 */
	public static function create(Connector $connector, string $groupName, string $instanceName): CloudWatch
	{
		$connector->getConnection()->createCloudWatch();

		$client = new CloudWatch(
			$connector->getConnection()->createCloudWatchLogs(),
			$groupName,
			$instanceName
		);

		return $client;
	}
}
