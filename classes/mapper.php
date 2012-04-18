<?php

abstract class Mapper implements MapperActions {

	protected $container;

	protected $identity;

	public function __construct(MapperContainer $container)
	{
		$this->container = $container;
	}

	protected function identity()
	{
		if ($this->identity === NULL)
		{
			$this->identity = new IdentityMap;
		}

		return $this->identity;
	}

	protected function container()
	{
		return $this->container;
	}

	protected function domain_name()
	{
		$class = get_class($this);
		$domain = str_replace('Mapper_', '', $class);
		$domain = substr($domain, strpos($domain, '_'));
		return $domain;
	}
	
	protected function collection_name()
	{
		$collection = strtolower($this->domain_name());
		return $collection;
	}

	protected function collection()
	{
		return $this->container()->connection()->selectCollection(
			$this->collection_name());
	}

	protected function model_class($suffix = NULL)
	{
		$model = "Model_{$this->domain_name()}";

		if ($suffix !== NULL)
		{
			$model .= "_{$suffix}";
		}

		return $model;
	}

	protected function is_valid_model(Model $model)
	{
		return $model instanceOf $this->model_class();
	}

	protected function assert_valid_model(Model $model)
	{
		if ( ! $this->is_valid_model($model))
		{
			throw new UnexpectedValueException(
				get_class($model).' should descend from '.$this->model_class());
		}
	}

}