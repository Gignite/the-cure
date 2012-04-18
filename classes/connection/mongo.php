<?php

class Connection_Mongo implements Connection {

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
	
	public function connect()
	{
		$connection = $this->config('connection', 'mongodb://127.0.0.1');
		$db = $this->config('db');
		return new Mongo("{$connection}/{$db}");
	}

}