<?php
/**
 * An accessor class for data transfer objects
 * 
 * @example
 * 
 *     $accessor = new ObjectAccessor;
 *     $model = new Models\User;
 *     $accessor->set($model, new Object(array(...)));
 *     $object = $accessor->get($model);
 *     
 *     // For convinience plain arrays are also supported
 *     $accessor->set($model, array(...));
 *
 * @package     TheCure
 * @category    ObjectAccessor
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure;

use TheCure\Models\Model;
use TheCure\Object;

class ObjectAccessor {

	public function get(Model $model)
	{
		$object = $model->__object();

		if ( ! $object)
		{
			$object = new Object;
			$this->set_object($model, $object);
		}

		return $object;
	}

	protected function set_object(Model $model, Object $object)
	{
		$model->__object($object);
	}

	public function set(Model $model, $object)
	{
		if (is_array($object))
		{
			$object = new Object($object);
		}

		$this->set_object($model, $object);
	}

}