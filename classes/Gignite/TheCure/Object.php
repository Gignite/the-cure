<?php
/**
 * A data transfer object
 *
 * @package     TheCure
 * @category    Object
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure;

class Object {

	protected $data;
	
	public function __construct(array $data = NULL, array $filter = NULL)
	{
		if ($filter)
		{
			$clean_data = array();

			foreach ($filter as $_field)
			{
				if (array_key_exists($_field, $data))
				{
					$clean_data[$_field] = $data[$_field];
				}
			}

			$data = $clean_data;
		}

		if ($data === NULL)
		{
			$data = array();
		}

		$this->data = $data;
	}

	public function get($field)
	{
		if (isset($this->data[$field]))
		{
			return $this->data[$field];
		}
	}

	public function set($field, $value)
	{
		$this->data[$field] = $value;
	}

	public function accessor($field, $value = NULL)
	{
		if ($value)
		{
			$this->set($field, $value);
			return;
		}

		return $this->get($field);
	}

	public function as_array()
	{
		return $this->data;
	}

	public function __isset($field)
	{
		return isset($this->data[$field]);
	}

	public function __get($field)
	{
		return $this->get($field);
	}

	public function __set($field, $value)
	{
		return $this->set($field, $value);
	}

}