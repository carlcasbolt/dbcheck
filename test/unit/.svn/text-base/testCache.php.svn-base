<?php

class testCache extends UnitTestCase
{
	private $_cache;

	public function setUp()
	{
		$this->_cache = Cache::getInstance();
	}
	
	public function tearDown()
	{
		unset($this->_cache);
	}

	public function testInit()
	{
		$this->assertTrue($this->_cache instanceof Cache);
	}
	
	public function testDefaultCacheTime()
	{
		$this->assertEqual($this->_cache->getCacheTime(), Cache::DEFAULT_CACHE_LIFE_TIME);
	}

	public function testCacheTimeInit()
	{
		$rand_cache_time = rand(1200, 4800);
		
		$cache = Cache::getInstance($rand_cache_time);
		$this->assertEqual($cache->getCacheTime(), $rand_cache_time);
	}

	public function testCacheTimeCanBeZero()
	{
		$cache = Cache::getInstance(0);
		$this->assertEqual($cache->getCacheTime(), 0);
	}

	public function testCacheTimeCannotBeNull()
	{
		$cache = Cache::getInstance(null);
		$this->assertNotEqual($cache->getCacheTime(), null);
		$this->assertEqual($cache->getCacheTime(), Cache::DEFAULT_CACHE_LIFE_TIME);
	}

	public function testGetValue()
	{
		$this->_cache->setValue('foobar', 'monkey');
		$this->assertEqual($this->_cache->getValue('foobar'), 'monkey');
	}

	public function testCacheFileHasExpired()
	{
		// 4 second cache
		$cache = Cache::getInstance(4);
		$this->_cache->setValue('foobar', 'monkey');
		$cache_file = $cache->getCacheFile('foobar');
		$this->assertFalse($cache->cacheFileHasExpired($cache_file));

		sleep(6);
		
		$this->assertTrue($cache->cacheFileHasExpired($cache_file));
	}
	
	public function testCleanKeyName()
	{
		$key = 'foo/bar null\\monkey';
		$this->assertEqual($this->_cache->cleanKeyName($key), 'foo_bar_null_monkey');
	}
}
