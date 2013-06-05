<?php

/**
 * ServerInformation 
 * 
 * @package DB Comparsion Tool
 * @copyright 2008 Carl Casbolt.
 * @author Carl Casbolt <carl.casbolt@gmail.com> 
 */
class ServerInformation
{
	private $server_list;

	public function __construct($server_list)
	{
		$this->server_list = $server_list;
	} // end of __construct()

	public function getServerStatus($server_name)
	{
		$url = @$this->server_list[$server_name];
		$online = false;

		// if there is no URL then don't waste time trying to read the local file.
		if($url != '' && strlen($url) > 7) {
			$stream = @fopen($url.'status.php', 'r');
			if ($stream) {
				$online = trim(stream_get_contents($stream)) == "ONLINE";
				@fclose($stream);
			}
		}
		return array(
			'name'   => $server_name,
			'url'    => $url,
			'online' => $online,
		);
	} // end of getServerStatus()

	public function getServerStatuses()
	{
		$key = 0;
		$server_statuses = array();
		foreach(array_keys($this->server_list) as $server_name) {
			$server_statuses[$key++] = $this->getServerStatus($server_name);
		}
		return $server_statuses;
	} // end of getServerStatuses()

	public function getOnlineServersList()
	{
		$key = 0;
		$online_servers = array();
		foreach($this->getServerStatuses() as $server) {
			if ($server['online']) {
				$online_servers[$key++] = $server;
			}
		}
		return $online_servers;
	}

} // end of class


