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

	protected function config()
	{
		static $config;

		if ($config === NULL)
		{
			$config = Kohana::$config->load("mappers.{$this->type()}");
		}

		return $config;
	}

	protected function connection()
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

	protected function mapper_class($mapper)
	{
		return "Mapper_{$this->type()}_{$mapper}";
	}

	public function mapper($class)
	{
		$class = $this->mapper_class($class);

		if ( ! isset($this->mappers[$class]))
		{
			$mapper = new $class;
			$mapper->connection($this->connection());
			$mapper->identities($this->identities());
			$mapper->config($this->config());

			$this->mappers[$class] = $mapper;
		}

		return $this->mappers[$class];
	}

}