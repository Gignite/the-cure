<?php

abstract class Mapper_Mongo implements Mapper {

	public function __construct(MapperContainer $container)
	{
		$this->container = $container;
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

	public function find_one($suffix, $id = NULL)
	{
		if ($id === NULL)
		{
			$id = $suffix;
			$suffix = NULL;
		}

		$options = $this->container()->query_options();
		$object = $this->collection()->findOne($id, $options);

		$class = $this->model_class($suffix);
		$model = new $class;
		$model->__object($object);
		return $model;
	}
	
	public function save($model)
	{
		if ( ! $model instanceOf $this->model_class())
		{
			throw new InvalidArgumentException(
				get_class($model).' should descend from '.$this->model_class());
		}

		$collection = $this->collection();
		$object = $model->__object();

		if (isset($object->_id))
		{
			$collection->update(array('_id' => $object->_id), $object);
		}
		else
		{
			$collection->insert($object);
		}
	}

}