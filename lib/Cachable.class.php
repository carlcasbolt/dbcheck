<?php

abstract class Cachable
{
	protected $cache;

	protected function getCache()
	{
		if (!$this->cache) {
			$this->setCache(Cache::getInstance());
		}
		return $this->cache;
	}

	protected function setCache($cache)
	{
		$this->cache = $cache;
	}
}