<?php
/**
 * A data transfer object
 * 
 * @example
 * 
 *     $object = new Object(array('name' => 'Luke'));
 *     
 *     $object->name = 'Alf';
 *     echo $object->name;
 *     
 *     $object->set('name', 'Bob');
 *     echo $object->get('name');
 *     
 *     $object->accessor('name', 'Jim');
 *     echo $object->accessor('name');
 *     
 *     var_dump($object->as_array());
 *     
 *     var_dump(isset($object->name));
 *     unset($object->name);
 *     
 *     // Filter input fields
 *     $_POST = array('name' => 'Luke', 'age' => 22);
 *     $object = new Object($_POST, array('name'));
 *     echo $object->name; // => 'Luke'
 *     echo $object->age; // => ''
 *
 * @package     TheCure
 * @category    Object
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure;

class Object {

	protected $data;
	
	/**
	 * Create a new Object optionally with an array of data.
	 *
	 * @param   array|null  of data
	 * @param   array|null  keys to filter with
	 */
	public function __construct(array $data = NULL, array $filter = NULL)
	{
		if ($data === NULL)
		{
			$data = array();
		}
		elseif ($filter)
		{
			$data = $this->filter($data, $filter);
		}

		$this->data = $data;
	}

	/**
	 * Filter an array by keys
	 *
	 * @param  array $data
	 * @param  array $filter
	 * @return array
	 */
	protected function filter(array $data, array $filter)
	{
		$clean_data = array();

		foreach ($filter as $_field)
		{
			if (array_key_exists($_field, $data))
			{
				$clean_data[$_field] = $data[$_field];
			}
		}

		return $clean_data;
	}

	/**
	 * Get a field's value.
	 *
	 * @param   string  field name
	 * @return  mixed   value
	 */
	public function get($field)
	{
		if (is_array($field))
		{
			$data = array();

			foreach ($field as $_field)
			{
				if (isset($this->data[$_field]))
				{
					$data[$_field] = $this->data[$_field];
				}
			}

			return $data;
		}
		elseif (isset($this->data[$field]))
		{
			return $this->data[$field];
		}
	}

	/**
	 * Set one or more field's value.
	 *
	 * @param   array   array  of $field => $value
	 * @param   string  field name
	 * @param   mixed   value
	 * @return  void
	 */
	public function set($field, $value = NULL)
	{
		if (is_array($field))
		{
			foreach ($field as $_field => $_val)
			{
				$this->data[$_field] = $_val;
			}
		}
		else
		{
			$this->data[$field] = $value;
		}
	}

	/**
	 * Delete a field.
	 * 
	 * @param   string  field name
	 * @return  void
	 */
	public function delete($field)
	{
		unset($this->data[$field]);
	}

	/**
	 * Set or get one field's value.
	 *
	 * @param   string  field name
	 * @param   mixed   value if setting
	 * @return  mixed   value if getting
	 */
	public function accessor($field, $value = NULL)
	{
		if ($value !== NULL)
		{
			$this->set($field, $value);
			return;
		}

		return $this->get($field);
	}

	/**
	 * Get Object's data as an array.
	 *
	 * @return  array
	 */
	public function as_array()
	{
		return $this->data;
	}

	/**
	 * Magic isset.
	 *
	 * @param   string  field name
	 * @return  boolean
	 */
	public function __isset($field)
	{
		return isset($this->data[$field]);
	}

	/**
	 * Magic get.
	 *
	 * @param   string  field name
	 * @return  mixed
	 */
	public function __get($field)
	{
		return $this->get($field);
	}

	/**
	 * Magic set.
	 *
	 * @param   string  field name
	 * @param   mixed   value
	 * @return  void
	 */
	public function __set($field, $value)
	{
		$this->set($field, $value);
	}

	/**
	 * Magic unset.
	 * 
	 * @param   string  field name
	 * @return  void
	 */
	public function __unset($field)
	{
		$this->delete($field);
	}

}