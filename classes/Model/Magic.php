<?php
/**
 * [!!] This file is full of sin
 */
abstract class Model_Magic extends Model {

	public static function fields()
	{
		return array();
	}

	protected $__container;

	public function __container(MapperContainer $container = NULL)
	{
		if ($container === NULL)
		{
			return $this->__container;
		}

		$this->__container = $container;
	}

	public function __call($method, $args)
	{
		$fields = static::fields();
		$object = $this->__object();
		$field = Arr::get($fields, $method);

		if ((($add = (strpos($method, 'add') === 0)
				AND $field = Arr::get($fields, substr($method, 4)))
			OR ($remove = (strpos($method, 'remove') === 0)
				AND $field = Arr::get($fields, substr($method, 7))))
			AND $field instanceOf Relationship_AddRemove)
		{
			if ($add)
			{
				return $field->add($this->__container(), $object, $args[0]);
			}
			else // if ($remove)
			{
				return $field->remove($this->__container(), $object, $args[0]);
			}
		}
		else
		{
			if (isset($object->{$field->name()}))
			{
				$value = $object->{$field->name()};
			}
			else
			{
				$value = NULL;
			}

			if ($field instanceOf Relationship)
			{
				return $field->find($this->__container(), $value);
			}
			else
			{
				return $value;
			}
		}

		throw new BadMethodCallException;
	}

}