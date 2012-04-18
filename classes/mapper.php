<?php

abstract class Mapper implements MapperActions {

	protected $container;

	protected $identities;

	public function __construct(MapperContainer $container)
	{
		$this->container = $container;
	}

	protected function identities()
	{
		if ($this->identities === NULL)
		{
			$this->identities = new IdentityMap;
		}

		return $this->identities;
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
	
	protected function create_collection($suffix, array $where, $callback)
	{
		if ($where === NULL)
		{
			if ($suffix === NULL OR is_string($suffix))
			{
				$where = array();
			}
			elseif (is_array($suffix))
			{
				$where = $suffix;
				$suffix = NULL;
			}
			else
			{
				throw new InvalidArgumentException;
			}
		}

		$cursor = call_user_func($callback, $this->collection(), $where);
		$class = $this->model_class($suffix);
		return new Collection_Model($cursor, $this->identities(), $class);
	}

	/**
	 * [!!] We probably always need to check to see if Model
	 *      actually exists even if pulled from identities.
	 */
	protected function create_model($suffix, $id, $callback)
	{
		if ($id === NULL)
		{
			$id = $suffix;
			$suffix = NULL;
		}
		
		$class = $this->model_class($suffix);

		if ($model = $this->identities()->get($class, $id))
		{
			// We got it
		}
		else
		{
			$object = call_user_func($callback, $this->collection(), $id);

			$model = new $class;
			$model->__object($object);
		}

		return $model;
	}

	public function save_model(Model $model, $callback)
	{
		$this->assert_valid_model($model);

		$collection = $this->collection();
		$object = $model->__object();

		call_user_func($callback, $this->collection(), $object);

		if ( ! $this->identities()->has($model))
		{
			$this->identities()->set($model);
		}
	}

	public function delete_model($model, $callback)
	{
		$collection = $this->collection();

		if ($model instanceOf Model)
		{
			$remove = array('_id' => $model->__object()->_id);
		}
		elseif ($model instanceOf Collection)
		{
			foreach ($model as $_id => $_model)
			{
				$this->delete($_id);
			}

			return;
		}
		else
		{
			$remove = $model;
		}
		
		call_user_func($callback, $this->collection(), $remove);

		if ($this->identities()->has($model))
		{
			$this->identities()->unset($model);
		}
	}

}