<?php
/**
 * An attribute of a model
 *
 *     // Enable setter method functionality
 *     $attributes = new Attributes(
 *         new Field('title'),
 *         new HasOne('owner'));
 *     
 *     $attributes->add(new Field('content'));
 *     $attributes->replace(new Field('content'));
 *
 * @package     TheCure
 * @category    Attribute
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure;

use Gignite\TheCure\Attribute\AliasTakenException;

class Attributes implements \ArrayAccess {

	protected $attributes = array();

	/**
	 * Create a new Attribute.
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
				throw new AliasTakenException($name);
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

	/**
	 * Remove attribute.
	 * 
	 * @param    string  attribute name
	 * @return   void
	 */
	public function remove($name)
	{
		$attribute = $this->get($name);
		$index = array_search($attribute, $this->attributes);
		unset($this->attributes[$index]);
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
	public function as_array()
	{
		return $this->attributes;
	}

}