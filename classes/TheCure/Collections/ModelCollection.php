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

use TheCure\Container;

use TheCure\Maps\IdentityMap;

use TheCure\TransferObjects\TransferObject;

use TheCure\Accessors\TransferObjectAccessor;

use TheCure\Models\MagicModel;

class ModelCollection extends IterableCollection {

	protected $identities;

	protected $classFactory;

	protected $container;

	/**
	 * @param $collection
	 * @param IdentityMap  $identities
	 * @param Callable     $classFactory
	 */
	public function __construct(
		$collection,
		IdentityMap $identities,
		$classFactory)
	{
		parent::__construct($collection);
		$this->identities = $identities;
		$this->classFactory = $classFactory;
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
	protected function classFactory()
	{
		return $this->classFactory;
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
		
		$classFactory = $this->classFactory();
		$identities = $this->identities();

		if ( ! $object instanceOf TransferObject)
		{
			$object = new TransferObject($object);
		}

		$class = $classFactory($object);

		if ($model = $identities->get($class, $this->key()))
		{
			// Done
		}
		else
		{
			$model = new $class;
			$accessor = new TransferObjectAccessor;
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