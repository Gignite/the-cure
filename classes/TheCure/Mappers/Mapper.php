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
	implements
		FindMapper,
		FindOneMapper,

		// FindAndMapper,
		// FindInMapper,
		// FindOneByIDMapper,
		// FindOrMapper,
		
		SaveMapper,
		
		DeleteMapper,

		FactorySetGet,
		IdentitiesSetGet,
		ConfigSetGet,
		ContainerSetGet {

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

	protected function idize($id)
	{
		return $id;
	}

	protected function identityNamespace()
	{
		return $this->factory()->domain($this);
	}

	protected function getModelFromIdentityMap($id)
	{
		return $this->identities()->get($this->identityNamespace(), $id);
	}

	protected function addModelToIdentityMap($model)
	{
		$this->identities()->set($this->identityNamespace(), $model);
	}

	protected function isHeldByIdentityMap($model)
	{
		return $this->identities()->has($this->identityNamespace(), $model);
	}

	protected function deleteFromIdentityMap($model)
	{
		return $this->identities()->delete($this->identityNamespace(), $model);
	}

	/**
	 * Get an instance of a Model.
	 *
	 *     $mapper->model();
	 *     
	 *     // With constructor args
	 *     $mapper->model(array('construct', array('args' => TRUE)));
	 *     
	 *     // With model and constructor args
	 *     $mapper->model('Admin', array('construct', array('args' => TRUE)));
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
			$class_factory,
			$this->identityNamespace());

		$collection->container($this->container());

		return $collection;
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

		$class = get_class($this);

		if ( ! is_array($where))
		{
			$where = array('_id' => $where);
		}

		if (isset($where['_id']))
		{
			$where['_id'] = $this->idize($where['_id']);

			if (count($where) === 1
				AND ! is_array($where['_id'])
				AND $model = $this->getModelFromIdentityMap($where['_id']))
			{
				return $model;
			}
		}

		$object = $callback($where);

		if ( ! isset($object->_id))
		{
			return;
		}

		if ( ! $model = $this->getModelFromIdentityMap($object->_id))
		{
			if (is_callable($suffix))
			{
				$suffix = $suffix($object);
			}

			$model = $this->model($suffix);

			if ($object)
			{
				$accessor = new TransferObjectAccessor;
				$accessor->set($model, $object);
			}

			$this->addModelToIdentityMap($model);
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

		if ( ! $this->isHeldByIdentityMap($model))
		{
			$this->addModelToIdentityMap($model);
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

		if ($this->isHeldByIdentityMap($model))
		{
			$this->deleteFromIdentityMap($model);
		}
	}

}