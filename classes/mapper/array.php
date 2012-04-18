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

			return self::$data;
		}

		self::$data[$this->collection_name()] = $collection;
	}

	public function find($suffix = NULL, array $where = array())
	{
		$collection = $this->collection();

		return $this->create_collection(
			$suffix,
			$where,
			function($where) use ($collection)
			{
				return $collection;
			});
	}

	public function find_one($suffix, $id = NULL)
	{
		$collection = $this->collection();

		return $this->create_model(
			$suffix,
			$id,
			function ($id) use ($collection)
			{
				return $collection[$id];
			});
	}
	
	public function save(Model $model)
	{
		$collection = $this->collection();

		$this->save_model($model, function ($object) use (& $collection)
		{
			$collection[$object->_id] = $object;
		});

		$this->collection($collection);
	}

	public function delete($model)
	{
		$collection = $this->collection();

		$this->delete_model($model, function ($object) use ($collection)
		{
			unset($collection[$remove]);
		});

		$this->collection($collection);
	}

}