<?php
namespace Gignite\TheCure\Models;

use Gignite\TheCure\Mapper\Container;
use Gignite\TheCure\Relationships\Relationship;
use Gignite\TheCure\Relationships\Relation;

/**
 * [!!] This file is full of sin
 */
abstract class Magic extends Model {

	public static function fields()
	{
		return array();
	}

	protected $__container;

	public function __container(Container $container = NULL)
	{
		if ($container === NULL)
		{
			return $this->__container;
		}

		$this->__container = $container;
	}

	private function relation_action($fields, $method, array $args = NULL)
	{
		if (isset($fields[$method]) AND $args)
		{
			$verb = 'Set';
			$key  = $method;
		}
		else
		{
			$verb = current(explode('_', $method));
			$key  = substr($method, strlen($verb) + 1);
		}

		$interface = ucfirst($verb);
		$interface = "Gignite\\TheCure\\Relation\\{$interface}";

		if (interface_exists($interface)
			AND isset($fields[$key])
			AND $fields[$key] instanceOf $interface)
		{
			return array($fields[$key], $verb);
		}
	}

	// private function add_relation($fields, $method)
	// {
	// 	if (strpos($method, 'add') === 0
	// 		AND $field = Arr::get($fields, substr($method, 4))
	// 		AND $field instanceOf Relation\Add)
	// 	{
	// 		return $field;
	// 	}
	// }

	// private function remove_relation($fields, $method)
	// {
	// 	if (strpos($method, 'remove') === 0
	// 		AND $field = Arr::get($fields, substr($method, 7))
	// 		AND $field instanceOf Relation\Remove)
	// 	{
	// 		return $field;
	// 	}
	// }

	// private function set_relation($fields, $method, array $args)
	// {
	// 	if ($field = Arr::get($fields, $method)
	// 		AND $field instanceOf Relation\Set
	// 		AND $args)
	// 	{
	// 		return $field;
	// 	}
	// }

	public function __call($method, $args)
	{
		$fields = static::fields();
		$object = $this->__object();
		
		if (isset($fields[$method]) AND ! $args)
		{
			$field = $fields[$method];

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
		elseif ($field_action = $this->relation_action($fields, $method, $args))
		{
			list($field, $action) = $field_action;
			$field->{$action}($this->__container(), $object, $args[0]);
		}
		else
		{
			throw new \BadMethodCallException;
		}
	}

}