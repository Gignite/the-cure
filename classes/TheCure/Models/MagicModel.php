<?php
/**
 * Add some magic into your Model
 *
 * [!!] This file is full of sin
 *
 * @package     TheCure
 * @category    Model
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Models;

use TheCure\Container;

use TheCure\Lists\AttributeList;

use TheCure\Attributes\Field;

use TheCure\TransferObjects\TransferObject;

use TheCure\Accessors\TransferObjectAccessor;

use TheCure\Relationships\Relationship;
use TheCure\Relationships\Relations;

abstract class MagicModel extends Model {

	/**
	 * Attributes for this Model.
	 * 
	 * @return  Attributes
	 */
	public static function attributes()
	{
		return new AttributeList;
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
	private function attribute(AttributeList $attributes, $method)
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
	private function relationship(AttributeList $attributes, $method, array $args)
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
        	$spaced_method = preg_replace('/([a-z])([A-Z])/', '$1 $2', $method);
			$verb = current(explode(' ', $spaced_method, 2));
			$relationship = $this->attribute(
				$attributes,
				substr(strtolower($method), strlen($verb)));
		}

		$arg = current($args);

		$interface = ucfirst($verb);
		$interface = "TheCure\\Relations\\{$interface}Relation";

		if (interface_exists($interface)
			AND $relationship instanceOf $interface)
		{
			return $relationship->{$verb}($this->__container(), $this, $arg);
		}

		return FALSE;
	}

	private $attributes;

	private function _attributes()
	{
		if ($this->attributes === NULL)
		{
			$this->attributes = static::attributes();
		}

		return $this->attributes;
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
		$attributes = $this->_attributes();
		$accessor = new TransferObjectAccessor;
		$object = $accessor->get($this);
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
				return $accessor->getFieldValue($this, $field);
			}
		}
		else
		{
			throw new \BadMethodCallException(
				get_class($this).'::'.$method.'()');
		}
	}

}