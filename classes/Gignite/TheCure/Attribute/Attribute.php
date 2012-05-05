<?php
/**
 * An attribute of a model
 *
 *     // Enable setter method functionality
 *     new Attribute('name')
 *
 *     // Provide an alias
 *     new Attribute('location', array('alias' => 'town'));
 *
 * @package     TheCure
 * @category    Attribute
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure\Attribute;

abstract class Attribute {

	protected $name;

	protected $alias;

	/**
	 * Create a new Attribute.
	 *
	 * @param   string  field name (as in database)
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
	 * Alias name.
	 *
	 * @return  string
	 */
	public function alias()
	{
		if ($this->alias)
		{
			return $this->alias;
		}
		
		return $this->name();
	}

}