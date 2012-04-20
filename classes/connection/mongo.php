<?php

class Connection_Mongo implements Connection {

	protected $connection;

	public function __construct(array $config)
	{
		$this->config = $config;
	}

	protected function config($key, $default = NULL)
	{
		if ($key === NULL)
		{
			return $this->config;
		}

		return Arr::get($this->config, $key, $default);
	}
	
	protected function connect()
	{
		return new Mongo($this->config('connection', 'mongodb://127.0.0.1'));
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