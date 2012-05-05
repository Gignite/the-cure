<?php
/**
 * A field
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
 * @package     TheCure
 * @category    Attribute
 * @category    Field
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure;

use Gignite\TheCure\Attribute\Attribute;

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