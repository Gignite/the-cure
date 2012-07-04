<?php
/**
 * An abstract mapper
 *
 *     $mapper->find(array('name' => 'Luke'));
 *     $mapper->find(array('name' => 'Luke'), 'Admin');
 *
 * @package     TheCure
 * @category    Mapper
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Mappers;

use TheCure\Factories\Factory;
use TheCure\Maps\IdentityMap;
use TheCure\Accessors\TransferObjectAccessor;

use TheCure\Container;

use TheCure\Mapper\Actions as MapperActions;
use TheCure\Mapper\ContainerSetGet;
use TheCure\Mapper\FactorySetGet;
use TheCure\Mapper\IdentitiesSetGet;
use TheCure\Mapper\ConfigSetGet;

use TheCure\Models\Model;
use TheCure\Models\MagicModel;

use TheCure\Collections\Collection;
use TheCure\Collections\ModelCollection;

abstract class Mapper
	implements MapperActions, FactorySetGet, IdentitiesSetGet,
		ConfigSetGet, ContainerSetGet {

	protected $container;
	protected $identities;
	protected $factory;
	protected $config;

	/**
	 * @param   Container|null  $container
	 * @return  mixed
	 */
	public function container(Container $container = NULL)
	{
		if ($container === NULL)
		{
			return $this->container;
		}

		$this->container = $container;
	}

	/**
	 * @param IdentityMap|null $identities
	 * @return mixed
	 */
	public function identities(IdentityMap $identities = NULL)
	{
		if ($identities === NULL)
		{
			return $this->identities;
		}

		$this->identities = $identities;
	}

	/**
	 * @param $config
	 * @return mixed
	 */
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

	/**
	 * @param  Factory|null $factory
	 * @return mixed
	 */
	public function factory(Factory $factory = NULL)
	{
		if ($factory === NULL)
		{
			return $this->factory;
		}

		$this->factory = $factory;
	}

	/**
	 * @return string
	 */
	protected function collectionName()
	{
		$collection = strtolower($this->factory()->domain($this));
		return $collection;
	}

	/**
	 * Get an instance of a Model.
	 * 
	 * @return   Model
	 */	
	public function model($suffix = NULL, array $args = array())
	{
		if (is_array($suffix))
		{
			$args = $suffix;
			$suffix = NULL;
		}

		$class = $this->factory()->model($this, $suffix);
		$reflection = new \ReflectionClass($class);
		$model = $reflection->newInstanceArgs($args);

		if ($model instanceOf MagicModel)
		{
			$model->__container($this->container());
		}

		return $model;
	}

	/**
	 * @example
	 *     
	 *     $suffix = function ($object)
	 *     {
	 *         if ($object->type === User::TYPE_ADMIN)
	 *         {
	 *             $suffix = 'User\Admin';
	 *         }
	 *         else
	 *         {
	 *             $suffix = 'User\User';
	 *         }
	 *         
	 *         return $suffix;
	 *     };
	 *     $this->find(NULL, $suffix);
	 *     
	 * @param  $where
	 * @param  $suffix
	 * @param  $callback
	 * @return Collections\Model
	 * @throws \InvalidArgumentException
	 */
	protected function createCollection($where, $suffix, $callback)
	{
		if ($where === NULL)
		{
			$where = array();
		}

		$cursor = $callback($where);

		$factory = $this->factory();
		$mapper = $this;

		$class_factory = function ($object) use ($factory, $mapper, $suffix)
		{
			if (is_callable($suffix))
			{
				$suffix = $suffix($object);
			}

			return $factory->model($mapper, $suffix);
		};

		$collection = new ModelCollection(
			$cursor,
			$this->identities(),
			$class_factory);

		$collection->container($this->container());

		return $collection;
	}

	protected function idize($id)
	{
		return $id;
	}

	/**
	 * [!!] We probably always need to check to see if Model
	 *      actually exists even if pulled from identities.
	 *
	 * @param  $suffix
	 * @param  $where
	 * @param  $callback
	 * @return mixed
	 */
	protected function createModel($where, $suffix, $callback)
	{
		if ($where === NULL)
		{
			$where = array();
		}

		if ( ! is_array($where))
		{
			$where = array('_id' => $this->idize($where));
		}
		elseif (isset($where['_id']))
		{
			$where['_id'] = $this->idize($where['_id']);
		}

		$object = $callback($where);

		if ( ! isset($object->_id))
		{
			return;
		}

		$identities = $this->identities();

		if (is_callable($suffix))
		{
			$suffix = $suffix($object);
		}

		$class = $this->factory()->model($this, $suffix);

		if ( ! $model = $identities->get($class, $object->_id))
		{
			$model = $this->model($suffix);

			if ($object)
			{
				$accessor = new TransferObjectAccessor;
				$accessor->set($model, $object);
			}

			$identities->set($model);
		}

		return $model;
	}

	/**
	 * @param Model $model
	 * @param       $callback
	 */
	protected function saveModel(Model $model, $callback)
	{
		$accessor = new TransferObjectAccessor;
		$object = $accessor->get($model);
		$object = call_user_func($callback, $object);
		$accessor->set($model, $object);

		if ($model instanceOf MagicModel
			AND $container = $this->container())
		{
			$model->__container($container);
		}

		if ( ! $this->identities()->has($model))
		{
			$this->identities()->set($model);
		}
	}

	/**
	 * @param  $model
	 * @param  $callback
	 * @return mixed
	 */
	protected function deleteModel($model, $callback)
	{
		if ($model instanceOf Model)
		{
			$accessor = new TransferObjectAccessor;
			$id = $accessor->get($model)->_id;
			$remove = array('_id' => $id);
		}
		elseif ($model instanceOf Collection)
		{
			foreach ($model as $_model)
			{
				$this->deleteModel($_model, $callback);
			}
			return;
		}
		else
		{
			$this->deleteModel($this->find($model), $callback);
			return;
		}

		call_user_func($callback, $remove);

		if ($this->identities()->has($model))
		{
			$this->identities()->delete($model);
		}
	}

}