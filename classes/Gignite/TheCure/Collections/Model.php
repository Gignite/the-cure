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
namespace Gignite\TheCure\Collections;

use Gignite\TheCure\IdentityMap;
use Gignite\TheCure\Object;

class Model extends Iterable {

	protected $identities;

	protected $class_name;

	/**
	 * @param $collection
	 * @param \Gignite\TheCure\IdentityMap $identities
	 * @param $class_name
	 */
	public function __construct(
		$collection,
		IdentityMap $identities,
		$class_name)
	{
		parent::__construct($collection);
		$this->identities = $identities;
		$this->class_name = $class_name;
	}

	/**
	 * @return \Gignite\TheCure\IdentityMap
	 */
	protected function identities()
	{
		return $this->identities;
	}

	/**
	 * @return mixed
	 */
	protected function class_name()
	{
		return $this->class_name;
	}

	/**
	 * @return \Gignite\TheCure\Models\Model|mixed
	 */
	public function current()
	{
		if ( ! $object = parent::current())
		{
			return;
		}
		
		$class = $this->class_name();

		if ($model = $this->identities()->get($class, $this->key()))
		{
			// Done
		}
		else
		{
			if ( ! $object instanceOf Object)
			{
				$object = new Object($object);
			}
			
			$model = new $class;
			$model->__object($object);
			$this->identities()->set($model);
		}

		return $model;
	}

}