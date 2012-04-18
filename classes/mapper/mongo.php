<?php

abstract class Mapper_Mongo extends Mapper {

	protected function query_options()
	{
		return $this->container()->query_options();
	}

	public function find($suffix = NULL, array $where = array())
	{
		$options = $this->query_options();

		return $this->create_collection($suffix, $where, function($collection, $where) use ($options)
		{
			return $collection->find($where, $options);
		});
	}

	public function find_one($suffix, $id = NULL)
	{
		$options = $this->query_options();

		return $this->create_model($suffix, $id, function ($collection, $id) use ($options)
		{
			return $collection->findOne($id, $options);
		});
	}
	
	public function save($model)
	{
		$options = $this->query_options();

		$this->save_model($model, function ($collection, $object) use ($options)
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
		$options = $this->query_options();

		$this->delete_model($model, function ($collection, $object) use ($options)
		{
			$collection->remove($remove, $options);
		});
	}

}