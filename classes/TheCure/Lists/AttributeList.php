<?php
/**
 * An attribute container
 *
 *     $attributes = new Attributes(
 *         new Field('title'),
 *         new HasOne('owner'));
 *     
 *     $attributes->add(new Field('content'));
 *     $attributes->replace(new Field('content'));
 *
 * @package     TheCure
 * @category    Attributes
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Lists;

use TheCure\Attributes\Attribute;
use TheCure\Exceptions\AliasUsedException;
use TheCure\Exceptions\AliasUnusedException;

class AttributeList implements \ArrayAccess {

	protected $attributes = array();

	/**
	 * Create a new AttributeList.
	 *
	 * @param   Attribute
	 * @return  void
	 */
	public function __construct()
	{
		$this->add(func_get_args());
	}

	/**
	 * Add one or more fields.
	 *
	 * @param   Attribute|array
	 * @return  void
	 */
	public function add($attributes)
	{
		if ( ! is_array($attributes))
		{
			$attributes = func_get_args();
		}

		foreach ($attributes as $_attribute)
		{
			if ($this->get($name = $_attribute->alias()))
			{
				throw new AliasUsedException($name);
			}

			$this->attributes[] = $_attribute;
		}
	}

	/**
	 * Get Attribute
	 *
	 * @param   string     attribute name
	 * @return  Attribute
	 */
	public function get($name)
	{
		foreach ($this->attributes as $_attribute)
		{
			if ($_attribute->alias() === $name)
			{
				return $_attribute;
			}
		}
	}

	// Get index of an Attribute
	protected function index(Attribute $attribute)
	{
		return array_search($attribute, $this->attributes);
	}

	/**
	 * Replace Attribute.
	 * 
	 * @param   Attribute|array
	 * @return  void
	 */
	public function replace($attributes)
	{
		if ( ! is_array($attributes))
		{
			$attributes = func_get_args();
		}

		foreach ($attributes as $_attribute)
		{
			if ( ! $previous = $this->get($name = $_attribute->alias()))
			{
				throw new AliasUnusedException($name);
			}

			$this->attributes[$this->index($previous)] = $_attribute;
		}
	}

	/**
	 * Remove attribute.
	 * 
	 * @param    Attribute|string
	 * @return   void
	 */
	public function remove($attribute)
	{
		if ( ! $attribute instanceOf Attribute)
		{
			$attribute = $this->get($attribute);
		}

		unset($this->attributes[$this->index($attribute)]);
	}

	/**
	 * Attribute exists?
	 * 
	 * @return  boolean
	 */
	public function offsetExists($name)
	{
		return (bool) $this->get($name);
	}

	/**
	 * Get attribute.
	 * 
	 * @return  boolean
	 */
	public function offsetGet($name)
	{
		return $this->get($name);
	}

	/**
	 * Set attribute.
	 * 
	 * @return  boolean
	 */
	public function offsetSet($name, $attribute)
	{
		$this->add($attribute);
	}

	/**
	 * Remove attribute.
	 * 
	 * @param  string  attribute name
	 */
	public function offsetUnset($name)
	{
		$this->remove($name);
	}

	/**
	 * Get attributes as array.
	 * 
	 * @return  array
	 */
	public function asArray()
	{
		return $this->attributes;
	}

}