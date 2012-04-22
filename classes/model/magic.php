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

		if ($field = Arr::get($fields, $method))
		{
			$value = $object->{$field->name()};

			if ($field instanceOf Field)
			{
				return $value;
			}
			elseif ($field instanceOf Relationship)
			{
				return $field->find($this->__container(), $value);
			}
		}
		elseif (strpos($method, 'add') === 0
			&& $field = Arr::get($fields, substr($method, 4)))
		{
			return $field->add($this->__container(), $object, $args[0]);
		}
		elseif (strpos($method, 'remove') === 0
			&& $field = Arr::get($fields, substr($method, 7)))
		{
			return $field->remove($this->__container(), $object, $args[0]);
		}

		throw new BadMethodName;
	}

}