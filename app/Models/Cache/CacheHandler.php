<?php

/**
 * @author Jiří Svěcený <sveceny@sitole.cz>
 * @copyright 2018 Jiří Svěcený
 * @version 25.12.2018
 */

declare(strict_types=1);

namespace App\Models\Cache;

use Nette\Caching\Cache;
use Nette\Caching\IStorage;


class CacheHandler
{
	/** @var Cache */
	private $cache;


	/**
	 * CacheHandler constructor
	 * @param IStorage $storage
	 */
	public function __construct(IStorage $storage)
	{
		$this->cache = new Cache($storage);
	}


	/**
	 * @param string $key
	 * @param callable $callback
	 * @param array|null $options
	 * @return mixed
	 */
	public function load(string $key, callable $callback, array $options = null)
	{
		$value = $this->cache->load($key);

		if ($value === null) {
			$value = $this->cache->save($key, call_user_func($callback), $options);
		}

		return $value;
	}


	/**
	 * Remove cache by key
	 * @param string $key
	 */
	public function remove(string $key): void
	{
		$this->cache->remove($key);
	}
}
