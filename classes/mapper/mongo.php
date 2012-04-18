<?php

abstract class Mapper_Mongo extends Mapper {

	protected function collection()
	{
		return $this->container()->connection()->selectCollection(
			$this->collection_name());
	}

	protected function query_options()
	{
		return $this->container()->config('query_options');
	}

	public function find($suffix = NULL, array $where = array())
	{
		$collection = $this->collection();
		$options = $this->query_options();

		return $this->create_collection(
			$suffix,
			$where,
			function($where) use ($collection, $options)
			{
				return $collection->find($where, $options);
			});
	}

	public function find_one($suffix, $id = NULL)
	{
		$collection = $this->collection();
		$options = $this->query_options();

		return $this->create_model(
			$suffix,
			$id,
			function ($id) use ($collection, $options)
			{
				return $collection->findOne($id, $options);
			});
	}
	
	public function save(Model $model)
	{
		$collection = $this->collection();
		$options = $this->query_options();

		$this->save_model($model, function ($object) use ($collection, $options)
		{
			if (isset($object->_id))
			{
				$collection->update(array('_id' => $object->_id), $object, $options);
			}
			else
			{
				$collection->insert($object, $options);
			}
		});
	}

	public function delete($model)
	{
		$collection = $this->collection();
		$options = $this->query_options();

		$this->delete_model($model, function ($object) use ($collection, $options)
		{
			$collection->remove($remove, $options);
		});
	}

}