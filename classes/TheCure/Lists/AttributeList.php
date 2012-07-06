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
			$name = $_attribute->alias();

			if ($this->get($name))
			{
				throw new AliasUsedException($name);
			}

			$this->attributes[$name] = $_attribute;
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
		if (isset($this->attributes[$name]))
		{
			return $this->attributes[$name];
		}
	}

	// Get index of an Attribute
	protected function index(Attribute $attribute)
	{
		return $attribute->alias();
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
			$name = $_attribute->alias();

			if ( ! $previous = $this->get($name))
			{
				throw new AliasUnusedException($name);
			}

			$this->attributes[$name] = $_attribute;
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

		$name = $attribute->alias();
		unset($this->attributes[$name]);
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