<?php

class MapperContainer {

	protected $connection;

	protected $mappers;
	
	public function __constuct($type)
	{
		$this->type = $type;
	}

	protected function config($key, $default = NULL)
	{
		$config = Kohana::$config->load('mgo.'.Kohana::$environment);

		if ($key === NULL)
		{
			return $config;
		}

		return Arr::get($config, $key, $default);
	}

	public function connection()
	{
		if ($this->connection === NULL)
		{
			$connection = $this->config('connection', 'mongodb://127.0.0.1');
			$db = $this->config('db');

			$this->connection = new Mongo("{$connection}/{$db}");
		}

		return $this->connection;
	}

	protected function type()
	{
		return $this->type;
	}

	protected function get_mapper_class($mapper)
	{
		return "Mapper_{$this->type()}_{$mapper}";
	}

	public function use($mapper)
	{
		$class = $this->get_mapper_class($mapper);

		if ( ! isset($this->mappers[$class]))
		{
			$this->mappers[$class] = new $class($this);
		}

		return $this->mappers[$class];
	}

}