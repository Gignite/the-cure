<?php

abstract class Relationship extends Field {

	protected $name;

	protected $mapper_class;

	protected $model_suffix;

	/**
	 *     $this->mapper_class = $mapper_class;
	 *     $this->model_suffix  = $model_suffix;
	 */
	public function __construct($name, array $config = NULL)
	{
		$this->name   = $name;

		if ($config)
		{
			foreach ($config as $_k => $_v)
			{
				if (property_exists($this, $_k))
				{
					$this->{$_k} = $_v;
				}
			}
		}
	}

	public function name()
	{
		return $this->name;
	}

	protected function model_suffix()
	{
		return $this->model_suffix;
	}

	protected function mapper_class()
	{
		return $this->mapper_class;
	}
	}

	abstract public function find(MapperContainer $container, $value);

}