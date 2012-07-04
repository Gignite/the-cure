<?php
/**
 * A field
 * 
 * @example
 *
 *     // A field with a name of "name"
 *     new Field('name');
 *
 *     // A field with a name of "location" but aliased to "town"
 *     new Field('name', array('alias' => 'town'));
 *
 *     // Provide a default value
 *     new Field('verified', array('value' => FALSE));
 *     
 *     // Add some rules to the field
 *     new Field('verified', array('rules' => array(
 *         array('not_empty'),
 *         array('min_length', array(':value', 2)),
 *     )));
 *
 * @package     TheCure
 * @category    Attribute
 * @category    Field
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Attributes;

use TheCure\Attributes\Attribute;

class Field extends Attribute {

	protected $value;

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
	 * Get field rules.
	 *
	 * @return  array
	 */
	public function rules()
	{
		return $this->rules;
	}

}