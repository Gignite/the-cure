<?php
/**
 * An array mapper for mocking tests
 * 
 *     $mapper->find($id);
 *     $mapper->find(array('name' => 'Luke'));
 *     $mapper->find('Admin', array('name' => 'Luke'));
 *
 * @package     TheCure
 * @category    Mappers
 * @copyright   Gignite, 2012
 */
namespace Gignite\TheCure\Mappers;

use Gignite\TheCure\Models\Model;

abstract class Mock extends Mapper {

	public $data = array();

	/**
	 * [!!] Using self::$data so that all mapper data is
	 *      shared against Mapper_Array and not sub classes.
	 */
	protected function collection(array $collection = NULL)
	{
		if ($collection === NULL)
		{
			return $this->data;
		}

		$this->data = $collection;
	}

	public static function each_where($collection, $where, $callback)
	{
		foreach ($collection as $_key => $_row)
		{
			foreach ($where as $_field => $_value)
			{
				if (is_array($_value)
					AND isset($_value['$in'])
					AND in_array($_row->{$_field}, $_value['$in']))
				{
					// This is okay
				}
				elseif (empty($_row->{$_field}) OR $_row->{$_field} !== $_value)
				{
					continue 2;
				}
			}

			if (call_user_func($callback, $_key) === FALSE)
			{
				break;
			}
		}
	}

	public function find($suffix = NULL, array $where = NULL)
	{
		$collection = $this->collection();

		return $this->create_collection(
			$suffix,
			$where,
			function($where) use ($collection)
			{
				$found = array();

				Mock::each_where(
					$collection,
					$where,
					function ($record) use ($collection, & $found)
					{
						$found[$record] = $collection[$record];
					});
				
				return new \ArrayIterator($found);
			});
	}

	public function find_one($suffix = NULL, $where = NULL)
	{
		$collection = $this->collection();

		return $this->create_model(
			$suffix,
			$where,
			function ($where) use ($collection)
			{
				if (isset($where['_id']))
				{
					return $collection[$where['_id']];
				}
				elseif ($where)
				{
					$found = NULL;
					Mock::each_where(
						$collection,
						$where,
						function ($record) use ($collection, & $found)
						{
							$found = $collection[$record];
							return FALSE;
						});
					return $found;
				}
				else
				{
					return current($collection);
				}
			});
	}
	
	public function save(Model $model)
	{
		$collection = $this->collection();

		$this->save_model($model, function ($object) use (& $collection)
		{
			if ( ! isset($object->_id))
			{
				$object->_id = count($collection);
			}

			$collection[$object->_id] = $object;
		});

		$this->collection($collection);
	}

	public function delete($model)
	{
		$collection = $this->collection();

		$this->delete_model($model, function ($where) use (& $collection)
		{
			unset($collection[$where['_id']]);
		});

		$this->collection($collection);
	}

}