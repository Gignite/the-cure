<?php

class MapperContainer {

	protected $connection;

	protected $identities;

	protected $mappers;
	
	public function __constuct($type)
	{
		$this->type = $type;
	}

	protected function type()
	{
		return $this->type;
	}

	public function config($key, $default = NULL)
	{
		static $config;

		if ($config === NULL)
		{
			$config = Kohana::$config->load("mappers.{$this->type()}");
		}

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
			$connection_class = "Connection_{$this->type()}";
			$this->connection = new $connection_class($this->config());
		}

		return $this->connection;
	}

	protected function identities()
	{
		if ($this->identities === NULL)
		{
			$this->identities = new IdentityMap;
		}

		return $this->identities;
	}

	protected function get_mapper_class($mapper)
	{
		return "Mapper_{$this->type()}_{$mapper}";
	}

	public function mapper($mapper)
	{
		$class = $this->get_mapper_class($mapper);

		if ( ! isset($this->mappers[$class]))
		{
			$this->mappers[$class] = new $class($this);
		}

		return $this->mappers[$class];
	}

}