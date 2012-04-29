<?php
/**
 * An abstract mapper
 * 
 *     $mapper->find($id);
 *     $mapper->find(array('name' => 'Luke'));
 *     $mapper->find('Admin', array('name' => 'Luke'));
 *
 * @package     TheCure
 * @category    Mappers
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure\Mappers;

use Gignite\TheCure\Factory;
use Gignite\TheCure\IdentityMap;
use Gignite\TheCure\Mapper\Actions as MapperActions;
use Gignite\TheCure\Mapper\FactorySetGet;
use Gignite\TheCure\Mapper\IdentitiesSetGet;
use Gignite\TheCure\Mapper\ConfigSetGet;
use Gignite\TheCure\Models\Model;
use Gignite\TheCure\Collections\Collection;
use Gignite\TheCure\Collections\Model as ModelCollection;

abstract class Mapper
	implements MapperActions, FactorySetGet, IdentitiesSetGet, ConfigSetGet {

	protected $identities;
	
	protected $factory;

	protected $config;

	public function identities(IdentityMap $identities = NULL)
	{
		if ($identities === NULL)
		{
			return $this->identities;
		}
		
		$this->identities = $identities;
	}

	public function config($config)
	{
		if (is_array($config))
		{
			$this->config = $config;
		}
		elseif ($this->config AND isset($this->config[$config]))
		{
			return $this->config[$config];
		}
	}

	public function factory(Factory $factory = NULL)
	{
		if ($factory === NULL)
		{
			return $this->factory;
		}

		$this->factory = $factory;
	}
	
	protected function collection_name()
	{
		$collection = strtolower($this->factory()->domain($this));
		return $collection;
	}

	protected function model_class($suffix = NULL)
	{
		return $this->factory()->model($this, $suffix);
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
				throw new \InvalidArgumentException;
			}
		}

		$cursor = call_user_func($callback, $where);
		$class = $this->model_class($suffix);
		return new ModelCollection($cursor, $this->identities(), $class);
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
			$id = $model->__object()->_id;
			$remove = array('_id' => $id);
		}
		elseif ($model instanceOf Collection)
		{
			foreach ($model as $_model)
			{
				$this->delete_model($_model, $callback);
			}
			return;
		}
		else
		{
			$this->delete_model($this->find($model), $callback);
			return;
		}
		
		call_user_func($callback, $remove);

		if ($this->identities()->has($model))
		{
			$this->identities()->delete($model);
		}
	}

}