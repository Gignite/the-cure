<?php
/**
 * A field
 *
 *     // Enable setter method functionality
 *     new Field('name', array('setter' => TRUE))
 *
 *     // Provide a default value
 *     new Field('verified', array('value' => FALSE));
 *
 * @package     TheCure
 * @category    Attribute
 * @category    Field
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure;

class Field extends Attribute {

	protected $value;

	protected $setter;

	protected $rules;

	/**
	 * Get default value.
	 *
	 * @return  mixed
	 */
	public function value()
	{
		return $this->value;
	}

	/**
	 * Is this field a setter?
	 *
	 * @return  boolean
	 */
	public function is_setter()
	{
		return $this->setter;
	}

	/**
	 * Get field rules.
	 *
	 * @return  array
	 */
	public function rules()
	{
		return $this->rules;
	}

}