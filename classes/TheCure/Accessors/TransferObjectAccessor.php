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
 * @category    Accessor
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Accessors;

use TheCure\Models\Model;

use TheCure\TransferObjects\TransferObject;

use TheCure\Attributes\Attribute;
use TheCure\Attributes\Field;

class TransferObjectAccessor {

	protected function setObject(Model $model, TransferObject $object)
	{
		$model->__object($object);
	}

	public function get(Model $model)
	{
		$object = $model->__object();

		if ( ! $object)
		{
			$object = new TransferObject;
			$this->setObject($model, $object);
		}

		return $object;
	}

	public function set(Model $model, $object)
	{
		if (is_array($object))
		{
			$object = new TransferObject($object);
		}

		$this->setObject($model, $object);
	}

	public function getFieldValue(Model $model, Field $field)
	{
		$object = $this->get($model);

		if (isset($object->{$field->name()}))
		{
			$value = $object->{$field->name()};
		}
		else
		{
			$value = $field->value();

			if (is_callable($value))
			{
				$value = $value($object);
			}
		}

		return $value;
	}

}