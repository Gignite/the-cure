<?php

abstract class Mapper implements MapperActions {

	protected $identities;

	protected $config;

	public function identities(IdentityMap $identities = NULL)
	{
		if ($identities === NULL)
		{
			return $this->identities;
		}
		
		$this->identities = $identities;
	}

	public function config($config, $default = NULL)
	{
		if (is_array($config))
		{
			$this->config = $config;
		}
		else
		{
			return Arr::get($this->config, $config, $default);
		}
	}

	protected function domain_name()
	{
		$class = get_class($this);
		$domain = str_replace('Mapper_', '', $class);
		$domain = substr($domain, strpos($domain, '_') + 1);
		return $domain;
	}
	
	protected function collection_name()
	{
		$collection = strtolower($this->domain_name());
		return $collection;
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
	
	protected function create_collection($suffix, $where, $callback)
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

		if ( ! $cursor = call_user_func($callback, $where))
		{
			$cursor = new ArrayIterator(array());
		}

		$class = $this->model_class($suffix);
		return new Collection_Model($cursor, $this->identities(), $class);
	}

	/**
	 * [!!] We probably always need to check to see if Model
	 *      actually exists even if pulled from identities.
	 */
	protected function create_model($suffix, $where, $callback)
	{
		if ($where === NULL)
		{
			if ($suffix === NULL OR is_string($suffix))
			{
				$where = array();
			}
			else
			{
				$where = $suffix;
				$suffix = NULL;
			}
		}

		if ( ! is_array($where))
		{
			$where = array('_id' => $where);
		}
		
		$class = $this->model_class($suffix);
		$object = call_user_func($callback, $where);

		if ( ! isset($object->_id))
		{
			return;
		}

		if ( ! $model = $this->identities()->get($class, $object->_id))
		{
			$model = new $class;
			$model->__object($object);
		}

		return $model;
	}

	protected function save_model(Model $model, $callback)
	{
		$object = $model->__object();
		$object = call_user_func($callback, $object);
		$model->__object($object);

		if ( ! $this->identities()->has($model))
		{
			$this->identities()->set($model);
		}
	}

	protected function delete_model($model, $callback)
	{
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
		
		call_user_func($callback, $remove);

		if ($this->identities()->has($model))
		{
			$this->identities()->delete($model);
		}
	}

}