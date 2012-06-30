<?php
/**
 * An array mapper for mocking tests
 * 
 *     $mapper->find(array('name' => 'Luke'));
 *     $mapper->find(array('name' => 'Luke'), 'Admin');
 *
 * @package     TheCure
 * @category    Mapper
 * @copyright   Gignite, 2012
 * @license     MIT
 */
namespace TheCure\Mappers;

use TheCure\Models\Model;

abstract class MockMapper extends Mapper {

	public $data = array();

	/**
	 * [!!] Using self::$data so that all mapper data is
	 *      shared against Mapper_Array and not sub classes.
	 *
	 * @param  array|null $collection
	 * @return array
	 */
	protected function collection(array $collection = NULL)
	{
		if ($collection === NULL)
		{
			return $this->data;
		}

		$this->data = $collection;
	}

	/**
	 * @static
	 * @param $collection
	 * @param $where
	 * @param $callback
	 */
	public static function eachWhere($collection, $where, $callback)
	{
		foreach ($collection as $_key => $_row)
		{
			foreach ($where as $_field => $_needle)
			{
				$value = $_row->get($_field);

				if (is_array($_needle)
					AND isset($_needle['$in'])
					AND in_array($value, $_needle['$in']))
				{
					// We were doing an $in query (like mongo)
				}
				elseif (is_array($value) AND in_array($_needle, $value))
				{
					// We were doing a contains query (like mongo)
				}
				elseif (is_string($_needle) AND strpos($_needle, '/') === 0 AND preg_match($_needle, $value))
				{
					// Regex search, and it matched
				}
				elseif ($value === NULL OR $value !== $_needle)
				{
					// We skip this result as it is either NULL
					// or doesn't match our criteria
					continue 2;
				}
			}

			if (call_user_func($callback, $_key) === FALSE)
			{
				break;
			}
		}
	}

	/**
	 * @param  array      $where
	 * @param  array|null $where
	 * @return Model|Collection
	 */
	public function find(array $where = NULL, $suffix = NULL)
	{
		$collection = $this->collection();

		return $this->createCollection(
			$where,
			$suffix,
			function($where) use ($collection)
			{
				$found = array();

				Mock::eachWhere(
					$collection,
					$where,
					function ($record) use ($collection, & $found)
					{
						$found[$record] = $collection[$record];
					});
				
				return new \ArrayIterator($found);
			});
	}

	/**
	 * @param  null $suffix
	 * @param  null $where
	 * @return mixed
	 */
	public function findOne($where = NULL, $suffix = NULL)
	{
		$collection = $this->collection();

		return $this->createModel(
			$where,
			$suffix,
			function ($where) use ($collection)
			{
				if (isset($where['_id']))
				{
					return $collection[$where['_id']];
				}
				elseif ($where)
				{
					$found = NULL;
					Mock::eachWhere(
						$collection,
						$where,
						function ($record) use ($collection, & $found)
						{
							$found = $collection[$record];
							return FALSE;
						});
					return $found;
				}
				elseif ($first = current($collection))
				{
					return $first;
				}
			});
	}

	/**
	 * @param Model $model
	 */
	public function save(Model $model)
	{
		$collection = $this->collection();

		$this->saveModel($model, function ($object) use (& $collection)
		{
			if ( ! isset($object->_id))
			{
				$object->_id = count($collection);
			}

			$collection[$object->_id] = $object;
			return $object;
		});

		$this->collection($collection);
	}

	/**
	 * @param $model
	 */
	public function delete($model)
	{
		$collection = $this->collection();

		$this->deleteModel($model, function ($where) use (& $collection)
		{
			unset($collection[$where['_id']]);
		});

		$this->collection($collection);
	}

}