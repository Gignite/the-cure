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

use Gignite\TheCure\Field;
use Gignite\TheCure\Mapper\Container;
use Gignite\TheCure\Relationships\Relationship;
use Gignite\TheCure\Relationships\Relation;

abstract class Magic extends Model {

	/**
	 * @static
	 * @return array
	 */
	public static function fields()
	{
		return array();
	}

	/**
	 * @var
	 */
	protected $__container;

	/**
	 * @param  Container|null $container
	 * @return mixed
	 */
	public function __container(Container $container = NULL)
	{
		if ($container === NULL)
		{
			return $this->__container;
		}

		$this->__container = $container;
	}

	private function field($fields, $method)
	{
		foreach ($fields as $_field)
		{
			if ($_field->alias() === $method)
			{
				return $_field;
			}
		}
	}

	/**
	 * @param            $fields
	 * @param            $method
	 * @param array|null $args
	 * @return array
	 */
	private function relation_action($fields, $method, array $args = NULL)
	{
		if ($field = $this->field($fields, $method) AND $args)
		{
			$verb = 'Set';
		}
		else
		{
			$verb = current(explode('_', $method));
			$field = $this->field($fields, substr($method, strlen($verb) + 1));
		}

		$interface = ucfirst($verb);
		$interface = "Gignite\\TheCure\\Relation\\{$interface}";

		if (interface_exists($interface)
			AND $field
			AND $field instanceOf $interface)
		{
			return array($field, $verb);
		}
	}

	/**
	 * @param string $method
	 * @param array  $args
	 * @return mixed|null
	 * @throws \BadMethodCallException
	 */
	public function __call($method, $args)
	{
		$fields = static::fields();
		$object = $this->__object();

		if ($field_action = $this->relation_action($fields, $method, $args))
		{
			list($field, $action) = $field_action;
			$field->{$action}($this->__container(), $this, $args[0]);
		}
		elseif ($field = $this->field($fields, $method))
		{
			if ($args)
			{
				$object->{$field->name()} = $args[0];
				return;
			}
			else
			{
				if (isset($object->{$field->name()}))
				{
					$value = $object->{$field->name()};
				}
				elseif ($field instanceOf Field)
				{
					$value = $field->value();
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