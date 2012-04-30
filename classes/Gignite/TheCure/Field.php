<?php
/**
 * A field
 *
 * @package     TheCure
 * @category    Field
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure;

class Field {

	protected $name;

	/**
	 * Create a new field.
	 *
	 * @param   string  field name
	 * @return  void
	 */
	public function __construct($name)
	{
		$this->name = $name;
	}

	/**
	 * Get field name.
	 *
	 * @return  string
	 */
	public function name()
	{
		return $this->name;
	}

}