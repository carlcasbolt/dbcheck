<?php

/**
 * Cache 
 * 
 * Cache handler class used to manage anything we want to cache between uses.
 * 
 * @package DB Comparsion Tool
 * @copyright 2008 Carl Casbolt.
 * @author Carl Casbolt <carl.casbolt@gmail.com> 
 */
class Cache
{
	/**
	 * There could be multiple instances of this cache tool. 
	 * Each instance is identified by it's cache_time value.
	 */
	static $instances = array();

	/**
	 * The location on disc that this class will attempt to read an write files from
	 */
	private $cache_path;

	/**
	 * The default cache timeout is 12 hours.
	 */
	const DEFAULT_CACHE_LIFE_TIME = 43200;

	/**
	 * @param   integer  $cache_time  - the number of seconds we want to cache something for.
	 * 
	 * @return  Cache
	 */
	private function __construct($cache_time = null)
	{
		if (!is_null($cache_time)) {
			$this->cache_time = $cache_time;
		} else {
			$this->cache_time = self::DEFAULT_CACHE_LIFE_TIME;
		}
		$this->cache_path = realpath(dirname(__FILE__) . '/..').'/cache/';
	} // end of __construct()

	/**
	 * Get the the maximum age (in seconds for any cahce file)
	 * 
	 * @return   integer  $cache_time  - the number of seconds we want to cache something for.
	 */
	public function getCacheTime()
	{
		return $this->cache_time;
	} // end of getCacheTime()

	/**
	 * Get an instance of this class based upon the $cache_time given.
	 * 
	 * @param   integer  $cache_time  - the number of seconds we want to cache something for.
	 * 
	 * @return  Cache
	 */
	public static function getInstance($cache_time = null)
	{
		if(!isset(self::$instances[$cache_time])) {
			self::$instances[$cache_time] = new Cache($cache_time);
		}
		return self::$instances[$cache_time];
	} // end of getInstance()

	/**
	 * Retrieve a cached value for a given key or return false
	 *
	 * Return false if:
	 * - The cache file does not exist
	 * - The cache file is too old
	 * 
	 * @param   string  $key  - the unique key for this cache item. Normally part of a filename
	 * 
	 * @return  mixed
	 */
	public function getValue($key)
	{
		$cache_file = $this->getCacheFile($key);
		
		// check the file time is within $this->cache_time of now
		if (!file_exists($cache_file) || $this->cacheFileHasExpired($cache_file)) {
			return false;
		}
		return unserialize(file_get_contents($cache_file));
	} // end of getValue()

	/**
	 * Cache a given value using the given key.
	 *
	 * - No errors are returned if destination is not writable.
	 * - No errors are returned if file is overritten or replaced.
	 * 
	 * key cannot contain any path information such as ../ or ..\
	 * 
	 * @param   string  $key    - the unique key for this cache item. Normally part of a filename
	 * @param   mixed   $value  - the data we want to cache
	 * 
	 * @return  void
	 */
	public function setValue($key, $value)
	{
		$cache_file = $this->getCacheFile($key);

		$fp = @fopen($cache_file, 'w');
		@fwrite($fp, serialize($value));
		@fclose($fp);
	} // end of setValue()

	/**
	 * @param   string  $key
	 * 
	 * @return  string
	 */
	public function getCacheFile($key)
	{
		return $this->cache_path . self::cleanKeyName($key) . ".cache";
	} // end of getCacheFile()

	/**
	 * @param   string  $cache_file
	 * 
	 * @return  string
	 */
	public function cacheFileHasExpired($cache_file)
	{
		return (time() - $this->getCacheTime()) > @filemtime($cache_file);
	}

	/**
	 * Clean up the key name for a cache item.
	 *
	 * - Remove any directory seperator to prevent cache files being written in random locations.
	 * 
	 * @param   string  $key  - the cache key to be cleaned
	 * 
	 * @return  string
	 */
	public static function cleanKeyName($key)
	{
		$characters = array('/', '\\', ' ');

		foreach ($characters as $character) {
			$key = str_replace($characters, '_', $key);
		}
		return $key;
	} // end of _cleanKeyName()

} // end of class


