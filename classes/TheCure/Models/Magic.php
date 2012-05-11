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
namespace TheCure\Models;

use TheCure\Attributes;
use TheCure\Field;
use TheCure\Object;
use TheCure\Container;
use TheCure\Relationships\Relationship;
use TheCure\Relationships\Relation;

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
	 * Get/set Container.
	 * 
	 * @param   Container  
	 * @return  mixed
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
	 * Get an attribute.
	 * 
	 * @param   array      of attributes
	 * @param   string     method called
	 * @return  Attribute
	 */
	private function attribute(Attributes $attributes, $method)
	{
		return $attributes->get($method);
	}

	/**
	 * Change or find a relationship.
	 * 
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
		$interface = "TheCure\\Relation\\{$interface}";

		if (interface_exists($interface)
			AND $relationship instanceOf $interface)
		{
			return $relationship->{$verb}($this->__container(), $this, $arg);
		}

		return FALSE;
	}

	/**
	 * Get value from $object or use default $field value.
	 * 
	 * @param   Object
	 * @param   Field
	 * @return  mixed
	 */
	private function value(Object $object, Field $field)
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

	/**
	 * The magic of this object!
	 * 
	 * @param   string  $method
	 * @param   array   $args
	 * @return  mixed
	 * @throws  BadMethodCallException
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
				return $this->value($object, $field);
			}
		}
		else
		{
			throw new \BadMethodCallException(
				get_class($this).'::'.$method.'()');
		}
	}

}