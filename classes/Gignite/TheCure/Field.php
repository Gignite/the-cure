<?php
/**
 * A field
 *
 *     // Enable setter method functionality
 *     new Field('name', array('setter' => TRUE))
 *
 * @package     TheCure
 * @category    Field
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure;

class Field {

	protected $name;

	protected $setter;

	/**
	 * Create a new field.
	 *
	 * @param   string  field name
	 * @param   array   additional config
	 * @return  void
	 */
	public function __construct($name, array $config = NULL)
	{
		$this->name = $name;

		if ($config)
		{
			foreach ($config as $_k => $_v)
			{
				if (property_exists($this, $_k))
				{
					$this->{$_k} = $_v;
				}
			}
		}
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

	/**
	 * Is this field a setter?
	 *
	 * @return  boolean
	 */
	public function is_setter()
	{
		return $this->setter;
	}

}