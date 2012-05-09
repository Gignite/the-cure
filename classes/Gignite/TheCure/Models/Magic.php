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

use Gignite\TheCure\Attributes;
use Gignite\TheCure\Field;
use Gignite\TheCure\Container;
use Gignite\TheCure\Relationships\Relationship;
use Gignite\TheCure\Relationships\Relation;

abstract class Magic extends Model {

	/**
	 * Attributes for this Model.
	 * 
	 * @return  Attributes
	 */
	public static function attributes()
	{
		return new Attributes;
	}

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

	/**
	 * @param   array      of attributes
	 * @param   string     method called
	 * @return  Attribute
	 */
	private function attribute(Attributes $attributes, $method)
	{
		return $attributes->get($method);
	}

	/**
	 * @param               $fields
	 * @param               $method
	 * @param   array|null  $args
	 * @return  array
	 */
	private function relationship(Attributes $attributes, $method, array $args)
	{
		if ($relationship = $this->attribute($attributes, $method))
		{
			if ($args)
			{
				$verb = 'set';
			}
			else
			{
				$verb = 'find';
			}
		}
		else
		{
			$verb = current(explode('_', $method));
			$relationship = $this->attribute(
				$attributes,
				substr($method, strlen($verb) + 1));
		}

		$arg = current($args);

		$interface = ucfirst($verb);
		$interface = "Gignite\\TheCure\\Relation\\{$interface}";

		if (interface_exists($interface)
			AND $relationship instanceOf $interface)
		{
			return $relationship->{$verb}($this->__container(), $this, $arg);
		}

		return FALSE;
	}

	/**
	 * @param   string  $method
	 * @param   array   $args
	 * @return  mixed|null
	 * @throws  \BadMethodCallException
	 */
	public function __call($method, $args)
	{
		$attributes = static::attributes();
		$object = $this->__object();
		$relationship = $this->relationship($attributes, $method, $args);

		if ($relationship !== FALSE)
		{
			return $relationship;
		}
		elseif ($field = $this->attribute($attributes, $method))
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
		else
		{
			throw new \BadMethodCallException(
				get_class($this).'::'.$method.'()');
		}
	}

}