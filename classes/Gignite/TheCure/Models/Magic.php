<?php
/**
 * Add some magic into your Model
 *
 * [!!] This file is full of sin
 *
 * @package     TheCure
 * @category    Models
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure\Models;

use Gignite\TheCure\Mapper\Container;
use Gignite\TheCure\Relationships\Relationship;
use Gignite\TheCure\Relationships\Relation;

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

	public function __call($method, $args)
	{
		$fields = static::fields();
		$object = $this->__object();
		
		if ($field_action = $this->relation_action($fields, $method, $args))
		{
			list($field, $action) = $field_action;
			$field->{$action}($this->__container(), $this, $args[0]);
		}
		elseif (isset($fields[$method]))
		{
			$field = $fields[$method];

			if ($args)
			{
				if ( ! $field->is_setter())
				{
					throw new \BadMethodCallException(
						'You cannot pass arguments to a non-setter field.');
				}

				$object->{$field->name()} = $args[0];
				return;
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
		}
		else
		{
			throw new \BadMethodCallException;
		}
	}

}