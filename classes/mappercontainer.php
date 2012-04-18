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

	protected function type()
	{
		return $this->type;
	}

	public function connection()
	{
		if ($this->connection === NULL)
		{
			$connection_class = "Connection_{$this->type()}";
			$this->connection = new $connection_class($this->config());
		}

		return $this->connection;
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