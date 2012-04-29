<?php
namespace Gignite\TheCure\Connections;

class Mongo implements Connection {

	protected $connection;

	public function __construct(array $config)
	{
		$this->config = $config;
	}

	protected function config($key, $default = NULL)
	{
		if (isset($this->config[$key]))
		{
			return $this->config[$key];
		}

		return $default;
	}
	
	protected function connect()
	{
		return new \Mongo($this->config('connection', 'mongodb://127.0.0.1'));
	}

	public function get()
	{
		if ($this->connection === NULL)
		{
			$this->connection = $this->connect();
		}

		return $this->connection->selectDb($this->config('db'));
	}

}