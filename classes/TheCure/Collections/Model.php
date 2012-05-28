<?php
/**
 * Describe an iterable collection
 * 
 * In order to iterate a set of data we use a [Collection].
 * 
 * @package     Beautiful
 * @subpackage  Beautiful Domain
 * @category    Collection
 * @category    Domain
 * @author      Luke Morton
 * @copyright   Luke Morton, 2011
 * @license     MIT
 */
namespace TheCure\Collections;

use TheCure\IdentityMap;
use TheCure\Object;
use TheCure\ObjectAccessor;
use TheCure\Models\Magic as MagicModel;
use TheCure\Container;

class Model extends Iterable {

	protected $identities;

	protected $class_name;

	protected $container;

	/**
	 * @param $collection
	 * @param IdentityMap  $identities
	 * @param Callable     $class_factory
	 */
	public function __construct(
		$collection,
		IdentityMap $identities,
		$class_factory)
	{
		parent::__construct($collection);
		$this->identities = $identities;
		$this->class_factory = $class_factory;
	}

	/**
	 * Get/set container.
	 * 
	 * @param   Container|NULL  $container
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
	 * @return IdentityMap
	 */
	protected function identities()
	{
		return $this->identities;
	}

	/**
	 * @return mixed
	 */
	protected function class_factory()
	{
		return $this->class_factory;
	}

	/**
	 * @return Model|mixed
	 */
	public function current()
	{
		if ( ! $object = parent::current())
		{
			return;
		}
		
		$class_factory = $this->class_factory();
		$identities = $this->identities();

		if ( ! $object instanceOf Object)
		{
			$object = new Object($object);
		}

		$class = $class_factory($object);

		if ($model = $identities->get($class, $this->key()))
		{
			// Done
		}
		else
		{
			$model = new $class;
			$accessor = new ObjectAccessor;
			$accessor->set($model, $object);

			if ($model instanceOf MagicModel
				AND $container = $this->container())
			{
				$model->__container($container);
			}

			$identities->set($model);
		}

		return $model;
	}

}