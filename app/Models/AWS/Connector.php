<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2019 Jiří Svěcený
 * @version 26.05.2019
 */

declare(strict_types=1);

namespace App\Models\AWS;

use Aws\Sdk;


class Connector
{
	/** @var array */
	private $options;


	/**
	 * Connector constructor
	 * @param string $region
	 * @param string $secretKey
	 * @param string $accessKey
	 */
	public function __construct(string $region, string $secretKey, string $accessKey)
	{
		$this->options = [
			'region' => $region,
			'version' => 'latest',
			'credentials' => [
				'key' => $accessKey,
				'secret' => $secretKey,
			],
		];
	}


	/**
	 * Get AWS sdk active connection
	 * @return Sdk
	 */
	public function getConnection(): Sdk
	{
		return new Sdk($this->options);
	}
}
