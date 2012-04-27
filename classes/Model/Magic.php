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

	private function add_relation($fields, $method)
	{
		if (strpos($method, 'add') === 0
			AND $field = Arr::get($fields, substr($method, 4))
			AND $field instanceOf Relationship_Add)
		{
			return $field;
		}
	}

	private function remove_relation($fields, $method)
	{
		if (strpos($method, 'remove') === 0
			AND $field = Arr::get($fields, substr($method, 7))
			AND $field instanceOf Relationship_Remove)
		{
			return $field;
		}
	}
	public function __call($method, $args)
	{
		$fields = static::fields();
		$object = $this->__object();
		
		if ($field = $this->add_relation($fields, $method))
		{
			$field->add($this->__container(), $object, $args[0]);
		}
		elseif ($field = $this->remove_relation($fields, $method))
		{
			$field->remove($this->__container(), $object, $args[0]);
		}
		elseif ($field = Arr::get($fields, $method))
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
		else
		{
			throw new BadMethodCallException;
		}
	}

}