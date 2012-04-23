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

		if ($field instanceOf Relationship_OneToMany
			AND ($add = (strpos($method, 'add') === 0)
				OR $remove = (strpos($method, 'remove') === 0)))
		{
			if ($add && $field = Arr::get($fields, substr($method, 4)))
			{
				return $field->add($this->__container(), $object, $args[0]);
			}
			elseif ($remove && $field = Arr::get($fields, substr($method, 7)))
			{
				return $field->remove($this->__container(), $object, $args[0]);
			}
		}
		else
		{
			$value = $object->{$field->name()};

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