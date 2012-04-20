<?php

abstract class Mapper_Array extends Mapper {

	public static $data = array();

	/**
	 * [!!] Using self::$data so that all mapper data is
	 *      shared against Mapper_Array and not sub classes.
	 */
	protected function collection(array $collection = NULL)
	{
		if ($collection === NULL)
		{
			if ( ! isset(self::$data[$this->collection_name()]))
			{
				self::$data[$this->collection_name()] = array();
			}

			return self::$data[$this->collection_name()];
		}

		self::$data[$this->collection_name()] = $collection;
	}

	public function find($suffix = NULL, array $where = NULL)
	{
		$collection = $this->collection();

		return $this->create_collection(
			$suffix,
			$where,
			function($where) use ($collection)
			{
				return new ArrayIterator($collection);
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
					// THIS SHOULD DO SOMETHING QUERY-LIKE
					return current($collection);
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
			if (isset($where['_id']))
			{
				unset($collection[$where['_id']]);
			}
			elseif ($where)
			{
				// THIS SHOULD DO SOMETHING QUERY-LIKE
				array_shift($collection);
			}
			else
			{
				array_shift($collection);
			}
		});

		$this->collection($collection);
	}

}